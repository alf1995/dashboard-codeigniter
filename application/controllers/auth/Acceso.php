<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acceso extends CI_Controller {

	public function __construct() {
        parent::__construct();

        $this->load->library(array('dashboard/auth'));
        $this->load->helper(array());
        $this->load->model(array('m_usuario')); 
        crawler_helper::detectar_bot();
    }

	public function panel()
	{
        $data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - Acceso';

        $countLog = 1;
        $this->auth->verifica_acceso_login();
        $this->auth->validar_ip();
		$this->load->view('auth/login', $data);
	}

	public function proceso($accion){
        switch($accion){
            case 'login':
                /* DATOS DE AJAX PRE PROCESADOS */
                $usuario = $this->input->post('usuario');
                $clave = $this->input->post('clave');
                /* VALIDACION DE PARAMETROS */
                $error = '';
                $error .= valida_campo($usuario, 'not_empty|email|maxlength:80', 'Usuario');
                $error .= valida_campo($clave, 'not_empty|maxlength:30', 'Contraseña');
                if($error != ''){ 
                    $message = sprintf(error_helper::msg()->msg201,$error);
                    echo alerta_error($message); exit();   
                }
                $this->auth->proceso($usuario,$clave);
                break;
            case 'logout':
                $this->auth->cerrar_session();
                break;
            default:
                return FALSE;
        }
    }
}
