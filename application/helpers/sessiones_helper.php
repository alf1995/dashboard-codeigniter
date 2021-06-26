<?php

class Sessiones_helper {

    static function encriptaInfo($valor){
        $ci =& get_instance();
        $librerias = array('mcrypted');
        $ci->load->library($librerias);
        $clave = md5(getenv('CF_KEY'));
        return $ci->mcrypted->doEncrypt($valor,$clave);
    }

    static function desencriptaInfo($valor){
        $ci =& get_instance();
        $librerias = array('mcrypted');
        $ci->load->library($librerias);
        $clave = md5(getenv('CF_KEY'));
        return $ci->mcrypted->doDecrypt($valor,$clave);
    }

    static function creaSesion($datos, $valor = NULL){
        $ci =& get_instance();
        $librerias = array('mcrypted');
        $ci->load->library($librerias);
        $clave = md5(getenv('CF_KEY'));
        @session_start();
        if(is_array($datos)){
            foreach($datos as $k => $v){
                unset($_SESSION[$k]);
                $_SESSION[$k] = $this->ci->mcrypted->doEncrypt($v,$clave);
            }
        } else{
            if(is_null($valor)){
                return FALSE;
            } else{
                unset($_SESSION[$datos]);
                $_SESSION[$datos] = $ci->mcrypted->doEncrypt($valor,$clave);
            }
        }
    }

    static function obtieneSesion($datos){
        $ci =& get_instance();
        $librerias = array('mcrypted');
        $ci->load->library($librerias);
        $clave = md5(getenv('CF_KEY'));
        @session_start();
        if(is_array($datos)){
            foreach($datos as $k => $v){
                if(isset($_SESSION[$k])){
                    $obtieneValor[] = $ci->mcrypted->doDecrypt($_SESSION[$k],$clave);
                } else{
                    return FALSE;
                }
            }
            return $obtieneValor;
        } else{
            if(isset($_SESSION[$datos])){
                $obtieneValor = $ci->mcrypted->doDecrypt($_SESSION[$datos],$clave);
            } else{
                return FALSE;
            }
            return $obtieneValor;
        }
    }

    static function eliminaSesion($datos) {
        @session_start();
        if(is_array($datos)){
            foreach ($datos as $k => $v) {
                unset($_SESSION[$k]);
            }
        } else{
            unset($_SESSION[$datos]);
        }
    }

    static function destruyeSesion() {
        @session_start();
        $_SESSION = array();
        @session_unset();
        @session_destroy();
        @session_regenerate_id(TRUE);
    }

    static function obtieneInfoSesion($arreglo = 'sesionUsuario'){
        $ci =& get_instance();
        $librerias = array('session');
        $ci->load->library($librerias);
        $obtieneSesion = $ci->session->all_userdata();
        if(isset($obtieneSesion[$arreglo]) && is_array($obtieneSesion[$arreglo])){
            $datos = new stdClass();
            foreach($obtieneSesion[$arreglo] as $k => $v){
                $datos->$k = $v;
            }
            return $datos;
        } else{
            return FALSE;
        }
    }
    
    static function destruyeInfoSesion($arreglo = 'sesionUsuario'){
        $ci =& get_instance();
        $librerias = array('session');
        $ci->load->library($librerias);
        $obtieneSesion = $ci->session->all_userdata();
        if(isset($obtieneSesion[$arreglo]) && is_array($obtieneSesion[$arreglo])){
            $ci->session->unset_userdata($arreglo);
            return TRUE;
        } else{
            return FALSE;
        }
    }

}
