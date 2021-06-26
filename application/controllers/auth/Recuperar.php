<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recuperar extends CI_Controller {

	private $items = array();

	public function __construct() {
        parent::__construct();

        $this->load->library(array('dashboard/auth','mailer'));
        $this->load->helper(array());
        $this->load->model(array('m_usuario','m_logs_email'));  

        $this->items['carpetaProyecto'] = config_admin()['directorySystem'];
        $this->items['getUrl'] = base_url().$this->items['carpetaProyecto'].'/'; 
    }

	public function panel()
	{
		$data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - Recuperar';
		$this->load->view('auth/send', $data);
	}

	public function update()
	{
		$data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - Actualizar Contraseña';
		$key = $this->input->get('key');
		$condiciones = array(
            'logs_email.token' => $key,
            'logs_email.desbloqueo >=' => time()
        );
        $obtieneEmail = $this->m_logs_email->mostrar_datos($condiciones);
        if(!empty($obtieneEmail)){
        	sessiones_helper::creaSesion('tokenContrasena', $key);
        	sessiones_helper::creaSesion('editarContrasena', $obtieneEmail[0]->usuario_id);
			$this->load->view('auth/update', $data);
        } else {
        	$this->load->view('auth/expire');
        }
		
	}

	public function proceso($accion){
        switch($accion){
            case 'enviar':
                /* DATOS DE AJAX PRE PROCESADOS */
                $email = $this->input->post('email');
                /* VALIDACION DE PARAMETROS */
                $error = '';
                $error .= valida_campo($email, 'not_empty|email|maxlength:80', 'Correo');
                if($error != ''){ 
                    $message = sprintf(error_helper::msg()->msg201,$error);
                    echo alerta_error($message); exit();   
                }
                $condicion = array(
		            'usuario.correo' => $email,
		        );
		        $resultado = $this->m_usuario->mostrar_datos($condicion);     
		        if(!empty($resultado)){
		        	$usuario = $resultado[0];
		        	$email = $usuario->correo;
					$templateData = [
						'nombre' => $usuario->nombre,
						'enlace' => base_url().'dashboard/recuperar/update?key='.$usuario->email_key
					]; 
					$this->mailer->to($email)->subject("Recuperar cuenta")->send("recuperar_cuenta.php", compact('templateData'));
					$tiempoBloqueo = (int) 60 * 60;
					$columnaEmail = array(
	                    'logs_email.ip_address' => $this->input->ip_address(), 
	                    'logs_email.usuario_id' => $usuario->id, 
	                    'logs_email.token' => $usuario->email_key, 
	                    'logs_email.fecha_registro' => time(), 
	                    'logs_email.desbloqueo' => (int) (time() + $tiempoBloqueo)
	                );
	                $this->m_logs_email->insertar($columnaEmail);
	                $columnaKey = array(
                        'email_key' => md5($usuario->correo.time()),
                    );
                    $this->m_usuario->actualizar($columnaKey, array( 'usuario.id' => $usuario->id ));
                    $message = sprintf(error_helper::msg()->msg201,'Se envio un correo de recuperación');
                    echo alerta_exito($message,3);EXIT;
		        } else {
		        	$message = sprintf(error_helper::msg()->msg201,'No se pudo procesar su solicitud');
                    echo alerta_error($message,3);EXIT; 
		        }
                break;
            case 'password':
                    $msjError = TRUE;
                    $tokenKey = sessiones_helper::obtieneSesion('tokenContrasena');
                    $usuarioId = sessiones_helper::obtieneSesion('editarContrasena');
                    $clave = $this->complementos->addSlashtag($this->input->post('clave',TRUE));
                    $reclave = $this->complementos->addSlashtag($this->input->post('reclave',TRUE));

                    $error = '';
                    $error .= valida_campo($clave, 'minlength:5|maxlength:30', 'Nueva contraseña');
                    $error .= valida_campo($reclave, 'not_empty|minlength:5|maxlength:30', 'Verificar contraseña');
                    $error .= valida_campo($clave, 'password::'.$reclave, 'Contraseña');
                    
                    if($error != ''){ 
                        $message = sprintf(error_helper::msg()->msg201,$error);
                        echo alerta_error($message);EXIT; 
                    }

                    $claveEncryptada = password_hash($reclave, PASSWORD_DEFAULT);
                    $claveFinal = $claveEncryptada;
                    $columnaDatos = array(
                        'clave' => $claveFinal,
                        'fecha_modificacion' => time(),
                    );
                    $resultado = $this->m_usuario->actualizar($columnaDatos, array( 'usuario.id' => $usuarioId ));
                    if(!empty($resultado)){
                    	$columnaKey = array(
	                        'desbloqueo' => time(),
	                    );
	                    $this->m_logs_email->actualizar($columnaKey, array( 'logs_email.token' => $tokenKey ));
                    	sessiones_helper::eliminaSesion('editarContrasena');
                        $message = sprintf(error_helper::msg()->msg201,'Se actualizo su contraseña correctamente');
                        $redUrl = $this->items['getUrl'].'/login';
                        echo alerta_exito($message,3,$redUrl);
                        EXIT();
                    }
                    if($msjError){
                        $message = sprintf(error_helper::msg()->msg6);
                        echo alerta_error($message);EXIT; 
                    }
                break;
            default:
                return FALSE;
        }
    }

}
