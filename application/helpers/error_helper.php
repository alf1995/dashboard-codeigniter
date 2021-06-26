<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error_helper {

    static function cargar(){
        $ci =& get_instance();
        $ci->message[1] = 'El %s no se encuentra(n) registrado(s).';
        $ci->message[2] = 'El %s no existe(n).';
        $ci->message[3] = 'El %s no se relaciona(n).';
        $ci->message[5] = 'Su %s ha(n) sido bloqueado(s).';
        $ci->message[6] = 'Hubo un error anómalo en el sistema y la ejecucion del proceso.';
        $ci->message[7] = 'El %s ya se encuentra(n) registrado(s).';
        $ci->message[8] = 'Es necesario ingresar un(a) %s para continuar.';
        $ci->message[9] = 'El %s y/o %s no deben de ser iguales.';
        $ci->message[10] = 'El %s que se ingreso no es un (int).';
        $ci->message[11] = 'El %s que se ingreso no es un (string).';
        $ci->message[12] = 'El %s que se ingreso no es un (boolean)';
        $ci->message[13] = 'El %s que se ingreso no es un (double/real).';
        $ci->message[14] = 'El %s que se ingreso no es un (array).';
        $ci->message[20] = 'El %s ha sido encontrado.';
        $ci->message[30] = 'El %s se registro temporalmente.';
        /* */
        $ci->message[201] = '%s';
        /* */
        $ci->message[501] = 'Acceso correcto, bienvenido %s a su panel.';
        $ci->message[502] = 'El proceso fue exitoso al agregar a %s en el sistema.';
        $ci->message[503] = 'El proceso fue exitoso al actualizar a %s en el sistema.';
        $ci->message[510] = 'Su correo ha sido enviado exitosamente.';
        $ci->message[600] = 'Usted ha sido desconectado.';
        /* */
        $ci->message[601] = 'Se ha exedido el tamaño permitido.';
        $ci->message[602] = 'No existe la ubicación seleccionada.';
        $ci->message[605] = 'Archivo subido con exito.';
        return $ci->message;
    }

    static function msg(){
        $obtieneErrores = error_helper::cargar();
        if(isset($obtieneErrores) && is_array($obtieneErrores)){
            $datos = new stdClass();
            foreach($obtieneErrores as $k => $v){
                $obtieneK = 'msg'.$k;
                $datos->$obtieneK = $v.'<br/> '
                        . '<i class="fa fa-bullhorn"></i> '
                        . 'MENSAJE DE ALERTA N°: <b>['.$k.']</b>';
            }
            return $datos;
        } else{
            return FALSE;
        }
    }

}
