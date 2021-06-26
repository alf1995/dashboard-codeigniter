<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth {

	private $items = array();

	public function __construct() {
        $this->ci =& get_instance();

        $librerias = array('dashboard/admin_lte');
        $helper = array();
        $modelos = array('m_usuario','m_logs_error','m_roles_permisos','m_roles_accion','m_roles_modulo');
        $this->ci->load->library($librerias);
        $this->ci->load->helper($helper);
        $this->ci->load->model($modelos);

        $this->items['carpetaProyecto'] = config_admin()['directorySystem'];
        $this->items['getUrl'] = base_url().$this->items['carpetaProyecto'].'/';
        $this->items = array_merge($this->items);
    }

    public function proceso($usuario = '',$clave = ''){
        $countLog = 0;
        $errorLogSess = sessiones_helper::obtieneSesion('errorLog');
        $errorLog = (!$errorLogSess) ? 0 : $errorLogSess;
        
        $intentosError = (int) config_admin()['insIntentoError'];
        $tiempoBloqueoError = (int) config_admin()['insTiempoBloqueo'] * 60;
    	if($errorLog >= $intentosError){
            $condiciones = array(
                'logs_error.ip_address' => $this->ci->input->ip_address(),
                'logs_error.desbloqueo >=' => time()
            );
            $obtieneLogs = $this->ci->m_logs_error->mostrar_datos($condiciones);
            if(empty($obtieneLogs)){
                $columnaDatos = array(
                    'logs_error.ip_address' => $this->ci->input->ip_address(), 
                    'logs_error.usuario' => $usuario, 
                    'logs_error.clave' => $clave, 
                    'logs_error.fecha_registro' => time(), 
                    'logs_error.desbloqueo' => (int) (time() + $tiempoBloqueoError)
                );
                $this->ci->m_logs_error->insertar($columnaDatos);
            }
            $message = sprintf(error_helper::msg()->msg5,'IP: '.$this->ci->input->ip_address());
            echo alerta_error($message,3); exit();
        } else
        if(!$this->ci->m_usuario->verifica_acceso($usuario, $clave)) {
            $countLog = $errorLog;
            $countLog++;
            sessiones_helper::creaSesion('errorLog', $countLog);
            $message = sprintf(error_helper::msg()->msg3, 'Usuario y Clave');
            echo alerta_error($message); exit();
        }

        $accesoUsuario = $this->ci->m_usuario->exito_acceso($usuario, $clave);
        if($accesoUsuario !== FALSE){
            $datos = array('sesionUsuario' => 
                array(
                    'accesoTmpId' => $accesoUsuario[0]->id, 
                    'accesoTmpUsuario' => $accesoUsuario[0]->correo,
                    'accesoTmpNombre'  => $accesoUsuario[0]->nombre,
                    'accesoTmpNivel' => $accesoUsuario[0]->rol_id, 
                    'accesoLoggedIn' => TRUE,
                    'accesoTmpTiempo'  => time() + 7200
                )
            );
            $this->ci->session->set_userdata($datos);
            $message = sprintf(error_helper::msg()->msg501, $accesoUsuario[0]->correo);
            $urlSucces = $this->items['getUrl'].'principal/panel';
            echo alerta_exito($message,2,$urlSucces); exit();
        } else{
            $message = sprintf(error_helper::msg()->msg6);
            echo alerta_error($message); exit();
        }
    }

    public function validar_ip(){
        $obtieneLogs = $this->ci->m_logs_error->mostrar_datos( 
            array( 
                'logs_error.ip_address' => $this->ci->input->ip_address(), 
                'logs_error.desbloqueo >=' => time() 
            )
        );
        if(empty($obtieneLogs)){
            sessiones_helper::eliminaSesion('errorLog');
        } else {
            echo show_404();
        }
    }

    public function cerrar_session(){
        sessiones_helper::destruyeInfoSesion('sesionUsuario');
        echo re_direccion($this->items['getUrl']); exit();
    }

    public function verifica_acceso(){
        $this->ci->accesoSession = sessiones_helper::obtieneInfoSesion('sesionUsuario');
        if(!isset($this->ci->accesoSession->accesoTmpId)){
            sessiones_helper::destruyeInfoSesion('sesionUsuario');
            echo re_direccion($this->items['getUrl'].'acceso/panel'); exit();
        } else {
            $condiciones = array( 'usuario.id' => $this->ci->accesoSession->accesoTmpId, 'usuario.eliminacion_logica' => 1 );
            $obtieneUsuario = $this->ci->m_usuario->mostrar_datos($condiciones);
            if(!empty($obtieneUsuario)){
                $obtieneUsuario = $obtieneUsuario[0];
                if(!empty($obtieneUsuario->imagen)){
                    $obtieneImagen = base_url().'crop/150/150/usuario-'.$obtieneUsuario->imagen;
                }else{
                    $obtieneImagen = base_url('public/panel/img/avatar.png');
                }
                $data['accesoTmpId'] = $obtieneUsuario->id;
                $data['accesoTmpImagen'] =  $obtieneImagen;
                $data['accesoTmpNombre'] = $obtieneUsuario->nombre;
            } else {
                sessiones_helper::destruyeInfoSesion('sesionUsuario');
                echo re_direccion($this->items['getUrl'].'acceso/panel'); exit();
            }
        }
        return $data;
    }

    public function verifica_acceso_login(){
        $this->ci->accesoSession = sessiones_helper::obtieneInfoSesion('sesionUsuario');
        if(isset($this->ci->accesoSession->accesoTmpId)){     
            $condiciones = array( 'usuario.id' => $this->ci->accesoSession->accesoTmpId, 'usuario.eliminacion_logica' => 1 );
            $obtieneUsuario = $this->ci->m_usuario->mostrar_datos($condiciones);
            if(!empty($obtieneUsuario)){
                echo re_direccion($this->items['getUrl'].'principal/panel'); exit();
            } else {
                sessiones_helper::destruyeInfoSesion('sesionUsuario');
                echo re_direccion($this->items['getUrl'].'acceso/panel'); exit();
            }
        }
    }

    public function formAccion($accion, $id, $eliminacionLogica = 1){
        $obtieneAccion = $accion;
        $defectoAccion = array(
            'entity' => 'usuario',
            'route' => base_url().$this->items['carpetaProyecto'], 
            'option' => 'observar|editar|denegar|permitir|remover', 
            'response' => 'respuesta', 
            'class' => 'procesarInfo'
        );
        $generaAccion = array_merge($defectoAccion, $obtieneAccion);
        $resultado = explode('|', $generaAccion['option']);
        $texto = '';
        foreach($resultado as $items){
            switch(trim($items)){
                case 'observar':
                        if($this->ci->auth->seguridadAccionModulo($this->ci->accesoSession->accesoTmpId,$this->ci->accesoSession->accesoTmpNivel, $generaAccion['entity'], 'observar')){
                            $texto .= '<small style="padding: 2px; display: inline-block;"><a href="'.$generaAccion['route'].'/'.$generaAccion['entity'].'/observar/'.$id.'" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="OBSERVAR ITEM">';
                            $texto .= '<span class="fas fa-eye"></span></a></small>';
                        break;
                    }
                case 'editar':
                    if($this->ci->auth->seguridadAccionModulo($this->ci->accesoSession->accesoTmpId,$this->ci->accesoSession->accesoTmpNivel, $generaAccion['entity'], 'editar')){
                        $texto .= '<small style="padding: 2px; display: inline-block;"><a href="'.$generaAccion['route'].'/'.$generaAccion['entity'].'/editar/'.$id.'" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="EDITAR ITEM">';
                        $texto .= '<i class="fas fa-edit" aria-hidden="true"></i></a></small>';
                    }
                    break;
                case 'denegar':
                    if($this->ci->auth->seguridadAccionModulo($this->ci->accesoSession->accesoTmpId,$this->ci->accesoSession->accesoTmpNivel, $generaAccion['entity'], 'denegar')){
                        if($eliminacionLogica == 1){
                            $texto .= '<small style="padding: 2px; display: inline-block;"><a href="javascript:;" data-url="'.$generaAccion['entity'].'/proceso/denegar/'.$id.'" data-response="'.$generaAccion['response'].'" class="btn btn-warning btn-sm '.$generaAccion['class'].'" data-toggle="tooltip" data-placement="bottom" title="DENEGAR ITEM">';

                            $texto .= '<i class="fas fa-lock"></i></a></small>';
                        }
                    }
                    break;
                case 'permitir':
                    if($this->ci->auth->seguridadAccionModulo($this->ci->accesoSession->accesoTmpId,$this->ci->accesoSession->accesoTmpNivel, $generaAccion['entity'], 'permitir')){
                        if($eliminacionLogica == 0){
                            $texto .= '<small style="padding: 2px; display: inline-block;"><a href="javascript:;" data-url="'.$generaAccion['entity'].'/proceso/permitir/'.$id.'" data-response="'.$generaAccion['response'].'" class="btn btn-warning btn-sm '.$generaAccion['class'].'" data-toggle="tooltip" data-placement="bottom" title="PERMITIR ITEM">';

                            $texto .= '<i class="fas fa-lock-open"></i></a></small>';
                        }
                    }
                    break;
                case 'remover':
                    if($this->ci->auth->seguridadAccionModulo($this->ci->accesoSession->accesoTmpId,$this->ci->accesoSession->accesoTmpNivel, $generaAccion['entity'], 'remover')){
                        if($eliminacionLogica == 0){
                            $texto .= '<small style="padding: 2px; display: inline-block;"><a href="javascript:;" data-url="'.$generaAccion['entity'].'/proceso/remover/'.$id.'" data-response="'.$generaAccion['response'].'" class="btn btn-danger btn-sm '.$generaAccion['class'].'" data-toggle="tooltip" data-placement="bottom" title="ELIMINAR ITEM">';
                            $texto .= '<i class="fas fa-trash"></i></a></small>';
                        } 
                    }
                    break;
                default:
                    return FALSE;
            }
        }
        return $texto;
    }



    public function seguridadAccionModulo($usuarioId,$nivelId,$modulo,$accion){
        if($nivelId != 500){
            $condicion = array(
                'roles_permisos.usuario_id' => $usuarioId,
                'roles_modulo.nombre' => $modulo,
                'roles_modulo.eliminacion_logica' => 1
            );
            $permisos = $this->ci->m_roles_permisos->mostrar_datos($condicion);
            if(!empty($permisos)){
                $obtieneDatos = $permisos[0];
                $jsonDecode = json_decode($obtieneDatos->acciones);
                foreach($jsonDecode as $k => $v){
                    $condicionAccion = array(
                        'roles_accion.id' => $v,
                        'roles_accion.eliminacion_logica' => 1 
                    );
                    $obtieneAccion = $this->ci->m_roles_accion->mostrar_datos($condicionAccion);
                    $obtieneAccionFinal = mb_strtolower($obtieneAccion[0]->nombre, 'UTF-8');
                    $accionFinal = mb_strtolower($accion, 'UTF-8');
                    if($obtieneAccionFinal == $accionFinal ){
                        return TRUE;
                    } else {
                        continue;
                    }
                }   
                return FALSE;
            } else{
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }

    public function crearMenu(){
        $menuOriginal = array(
            'MENÚ PRINCIPAL',
            'Principal|fas fa-tachometer-alt' => $this->items['getUrl'].'principal/panel',
            'MENÚ DE SISTEMA',
            'Usuarios|fa fa-users' => array(
                'Listar|fa fa-list-alt text-blue' => $this->items['getUrl'].'usuario/listar'
            ),
            'CONFIGURACIÓN',
            'Roles|fa fa-briefcase' => array(
                'Accion|far fa-circle text-blue' => $this->items['getUrl'].'accion/listar',
                'Modulo|far fa-circle text-blue' => $this->items['getUrl'].'modulo/listar',
                'Permisos|far fa-circle text-blue' => $this->items['getUrl'].'permisos/listar'
            ),
        );
        /* ----------------------------------------------------------- */
        $condiciones_modulo = array(
           
        );
        $getBdModulo = $this->ci->m_roles_modulo->mostrar_datos($condiciones_modulo);
        $configModulo = ($this->ci->accesoSession->accesoTmpNivel == 500) ? config_admin()['sisModulo'] : array();
        $obtieneModulo =  (!empty($getBdModulo)) ? $getBdModulo : $configModulo;
        if(!empty($obtieneModulo)){
            $roles_accion = $this->ci->m_roles_accion->mostrar_datos();
            $roles_accion = (!empty($roles_accion)) ? $roles_accion : config_admin()['sisAccion'];
            foreach ($obtieneModulo as $items) {
                $resultArrayRoles = array();
                $value_action = ($this->ci->accesoSession->accesoTmpNivel == 500) ? 1 : 0;
                if(!empty($roles_accion)){
                    foreach($roles_accion as $items_rol){
                        $obtieneAccionFinalRol = mb_strtolower($items_rol->nombre, 'UTF-8');
                        $resultArrayRoles[$obtieneAccionFinalRol] = (int) $value_action;
                    }  
                }
                $condiciones_permisos = array(
                    'roles_permisos.usuario_id' => $this->ci->accesoSession->accesoTmpId,
                    'roles_permisos.modulo_id' => $items->id,
                    'roles_modulo.eliminacion_logica' => 1
                );
                $obtienePermisos = $this->ci->m_roles_permisos->mostrar_datos($condiciones_permisos);
                if($this->ci->accesoSession->accesoTmpNivel  != 500){
                    if(!empty($obtienePermisos)){
                        foreach($obtienePermisos as $items_p) {
                            $jsonDecode = json_decode($items_p->acciones);
                            $resultArray = array();
                            if(!empty($jsonDecode)){
                               foreach((array)$jsonDecode as $k => $v){
                                    $condicionAccion = array(
                                        'roles_accion.id' => $v,
                                        'roles_accion.eliminacion_logica' => 1 
                                    );
                                    $obtieneAccion = $this->ci->m_roles_accion->mostrar_datos($condicionAccion);
                                    $obtieneAccionFinal = mb_strtolower($obtieneAccion[0]->nombre, 'UTF-8');
                                    $resultArray[$obtieneAccionFinal] = (int) 1;
                                }  
                            } 
                            $listaAccionesEnd = array_replace($resultArrayRoles, $resultArray); 
                        }
                    } else {
                        $listaAccionesEnd = $resultArrayRoles;
                    }
                } else {
                    $listaAccionesEnd = $resultArrayRoles;
                }
                $listaSeguridad[] = array(
                    'nivelUsuario' => (int) $items->id,
                    'moduloUsuario' => $items->nombre,
                    'accionesUsuario' => $listaAccionesEnd,
                );
            }
        } else {
            $listaSeguridad = array();
        }
        /* ----------------------------------------------------------- */
        $numeroArg = func_num_args();
        $listarArg = func_get_args();
        $cargarInfo = $this->ci->admin_lte->cargarInfo($listarArg, $numeroArg);
        /* ----------------------------------------------------------- */
        $filtrarMenu = $this->ci->admin_lte->filtrarMenu($menuOriginal, $cargarInfo);
        $permisosMenu = $this->ci->admin_lte->filtrarPermisoMenu($filtrarMenu, $listaSeguridad);
        /* ----------------------------------------------------------- */
        return $this->ci->admin_lte->generarMenu($permisosMenu);
    }

}