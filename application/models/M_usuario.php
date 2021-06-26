<?php

require_once APPPATH.'libraries/Model_DB.php';

class M_usuario extends Model_DB {

        public function __construct() {
            parent::__construct();
            parent::setTabla('usuario');
            parent::setId('id');
        }

        public function get_items_table()
        {
            $this->ci->db->select('*');
            $this->ci->db->from($this->tabla);
        }


        public function verifica_acceso($usuario, $clave){
            $condiciones = array(
                'correo' => $usuario,
                'eliminacion_logica' => 1,
            );
            $usuarioRegistro = $this->mostrar_datos($condiciones);
            if(!empty($usuarioRegistro) and strcmp($usuario, $usuarioRegistro[0]->correo) === 0){
                $resultado = password_verify($clave, $usuarioRegistro[0]->clave);
                if(!$resultado){
                    return FALSE;
                } else{
                    return TRUE;
                }
            }else{
                return FALSE;
            }
        }

        public function exito_acceso($usuario, $clave) {
            $condiciones = array(
                'correo' => $usuario,
                'eliminacion_logica' => 1,
            );
            $usuarioRegistro = $this->mostrar_datos($condiciones);
            $resultadoContrasena = password_verify($clave, $usuarioRegistro[0]->clave);
            if($resultadoContrasena){
                $condiciones = array(
                    'correo' => $usuario,
                );
                $resultado = $this->mostrar_datos($condiciones);
                if(!empty($resultado)){
                    return $resultado;
                } else{
                    return FALSE;
                }
            }
        }

}
