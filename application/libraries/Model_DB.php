<?php

class Model_DB {
    
    protected $ci;
    protected $tabla;
    protected $id;

    function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->database();
    }

    public function setTabla($tabla) {
        $this->tabla = $tabla;
    }
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function denegar_info($id){
        $columnaDatos = array(
            $this->tabla.'.eliminacion_logica' => 0
        );
        $condiciones = array(
            $this->tabla.'.'.$this->id => $id
        );
        $this->ci->db->where($condiciones);
        $resultado = $this->ci->db->update($this->tabla, $columnaDatos);
        if ($resultado){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function permitir_info($id){
        $columnaDatos = array(
            $this->tabla.'.eliminacion_logica' => 1
        );
        $condiciones = array(
            $this->tabla.'.'.$this->id => $id
        );
        $this->ci->db->where($condiciones);
        $resultado = $this->ci->db->update($this->tabla, $columnaDatos);
        if ($resultado){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function truncar(){
        $resultado = $this->ci->db->truncate($this->tabla);
        if ($resultado){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function eliminar($condicion = array()){
        if( (isset($condicion)) && (is_array($condicion)) ){
            if( (isset($condicion['and'])) || (isset($condicion['or'])) ){
                if( (isset($condicion['and'])) && (count($condicion['and']) > 0) ){
                    $this->ci->db->where($condicion['and']);
                }
                if( (isset($condicion['or'])) && (count($condicion['or']) > 0) ){
                    $this->ci->db->or_where($condicion['or']);
                }
            } else{
                $this->ci->db->where($condicion);
            }
        }
        $resultado = $this->ci->db->delete($this->tabla);
        if ($resultado){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function actualizar($datos = array(), $condicion = array()){
        if( (is_array($datos)) && (count($datos) > 0) ){
            $obtieneDatos = $datos;
        } else{
            return FALSE;
        }
        if( (isset($condicion)) && (is_array($condicion)) ){
            if( (isset($condicion['and'])) || (isset($condicion['or'])) ){
                if( (isset($condicion['and'])) && (count($condicion['and']) > 0) ){
                    $this->ci->db->where($condicion['and']);
                }
                if( (isset($condicion['or'])) && (count($condicion['or']) > 0) ){
                    $this->ci->db->or_where($condicion['or']);
                }
            } else{
                $this->ci->db->where($condicion);
            }
        }
        $resultado = $this->ci->db->update($this->tabla, $obtieneDatos);
        if ($resultado){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function insertar($datos = array(), $idInsertado = FALSE){
        if( (is_array($datos)) && (count($datos) > 0) ){
            $this->ci->db->trans_begin();
            $resultado = $this->ci->db->insert($this->tabla, $datos);
            $this->ci->db->trans_complete();
            if($idInsertado !== FALSE){
                if($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    return FALSE;
                }else{
                    $this->db->trans_commit();
                    return $this->db->insert_id();
                }
            }
            return TRUE;
        } else{
            return FALSE;
        }
    }
    
    public function mostrar_datos($condicion = array(), $limite = FALSE, $offset = FALSE ){
        $this->get_items_table();
        if( (isset($condicion)) && (is_array($condicion)) ){
            if( (isset($condicion['and'])) || (isset($condicion['or'])) ){
                if( (isset($condicion['and'])) && (count($condicion['and']) > 0) ){
                    foreach($condicion['and'] as $k => $v){
                        if(strtolower($v) == 'desc' || strtolower($v) == 'asc'){
                            $this->ci->db->order_by($k, strtolower($v));
                        } else{
                            $this->ci->db->where($k, $v);
                        }
                    }
                }
                if( (isset($condicion['or'])) && (count($condicion['or']) > 0) ){
                    foreach($condicion['or'] as $k => $v){
                        if(strtolower($v) == 'desc' || strtolower($v) == 'asc'){
                            $this->ci->db->order_by($k, strtolower($v));
                        } else{
                            $this->ci->db->or_where($k, $v);
                        }
                    }
                }
            } else{
                foreach($condicion as $k => $v){
                    if(strtolower($v) == 'desc' || strtolower($v) == 'asc'){
                        $this->ci->db->order_by($k, strtolower($v));
                    } else{
                        $this->ci->db->where($k, $v);
                    }
                }
            }
        }
        if( ($limite !== FALSE) && ($offset !== FALSE) ){
            $this->ci->db->limit($limite, $offset);
        }
        if( ($limite !== FALSE) ){
            $this->ci->db->limit($limite);
        }
        $obtieneResultado = $this->ci->db->get()->result();
        return $obtieneResultado;
    }

    public function buscar_datos($condicion = array(), $limite = FALSE, $offset = FALSE ){
        $this->get_items_table();
        if( (isset($condicion)) && (is_array($condicion)) ){
            if( (isset($condicion['and'])) || (isset($condicion['or'])) ){
                if( (isset($condicion['and'])) && (count($condicion['and']) > 0) ){
                    foreach($condicion['and'] as $k => $v){
                        if(strtolower($v) == 'desc' || strtolower($v) == 'asc'){
                            $this->ci->db->order_by($k, strtolower($v));
                        } else{
                            $this->ci->db->like($k, $v);
                        }
                    }
                }
                if( (isset($condicion['or'])) && (count($condicion['or']) > 0) ){
                    foreach($condicion['or'] as $k => $v){
                        if(strtolower($v) == 'desc' || strtolower($v) == 'asc'){
                            $this->ci->db->order_by($k, strtolower($v));
                        } else{
                            $this->ci->db->or_like($k, $v);
                        }
                    }
                }
            } else{
                foreach($condicion as $k => $v){
                    if(strtolower($v) == 'desc' || strtolower($v) == 'asc'){
                        $this->ci->db->order_by($k, strtolower($v));
                    } else{
                        $this->ci->db->like($k, $v);
                    }
                }
            }
        }
        if( ($limite !== FALSE) && ($offset !== FALSE) ){
            $this->ci->db->limit($limite, $offset);
        }
        if( ($limite !== FALSE) ){
            $this->ci->db->limit($limite);
        }
        $obtieneResultado = $this->ci->db->get()->result();
        return $obtieneResultado;
    }

    public function existe_info($columna, $valor, $id = FALSE){
        if($id === FALSE){
            $this->ci->db->select();
            $this->ci->db->from($this->tabla);
            $this->ci->db->where($this->tabla.'.'.$columna, $valor);
            $resultado = $this->ci->db->get()->result();
        } else{
            $this->ci->db->select($this->tabla.'.'.$columna.' as '.$this->tabla.'_'.$columna);
            $this->ci->db->from($this->tabla);
            $this->ci->db->where($this->tabla.'.'.$this->id, $id);
            $obtieneInfo = $this->ci->db->get()->result_array();
            /* --------------------------------------------------- */
            $this->ci->db->select();
            $this->ci->db->from($this->tabla);
            $this->ci->db->where($this->tabla.'.'.$columna.' !=', ($obtieneInfo[0][$this->tabla.'_'.$columna] == '') ? '' : $obtieneInfo[0][$this->tabla.'_'.$columna]);
            $this->ci->db->where($this->tabla.'.'.$columna, $valor);
            $resultado = $this->ci->db->get()->result();
        }
        if(!empty($resultado)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function existe_mucha_info($info, $id = FALSE){
        if($id === FALSE){
            $this->ci->db->select();
            $this->ci->db->from($this->tabla);
            foreach($info as $k => $v){
                $this->ci->db->where($this->tabla.'.'.$k, $v);
            }
            $resultado = $this->ci->db->get()->result();
        } else{
            foreach($info as $k => $v){
                $this->ci->db->select($this->tabla.'.'.$k.' as '.$this->tabla.'_'.$k);
            }
            $this->ci->db->from($this->tabla);
            $this->ci->db->where($this->tabla.'.'.$this->id, $id);
            $obtieneInfo = $this->ci->db->get()->result_array();
            /* --------------------------------------------------- */
            $this->ci->db->select();
            $this->ci->db->from($this->tabla);
            foreach($info as $k => $v){
                if(strpos($k, '_id') === FALSE){
                    $this->ci->db->where($this->tabla.'.'.$k.' !=', ($obtieneInfo[0][$this->tabla.'_'.$k] == '') ? '' : $obtieneInfo[0][$this->tabla.'_'.$k]); 
                }
                $this->ci->db->where($this->tabla.'.'.$k, $v);
            }
            $resultado = $this->ci->db->get()->result();
        }
        if(!empty($resultado)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function existe($id){
        $this->ci->db->select();
        $this->ci->db->from($this->tabla);
        $this->ci->db->where($this->tabla.'.'.$this->id, $id);
        $resultado = $this->ci->db->get()->result();
        if(!empty($resultado)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    public function contador($info = array()){
        $this->ci->db->select();
        $this->ci->db->from($this->tabla);
        foreach($info as $k => $v){
            $this->ci->db->where($this->tabla.'.'.$k, $v);
        }
        $resultado = $this->ci->db->count_all_results();
        return $resultado;
    }

    public function datos_mysql(){
        $info = array(
            'plataforma'  => $this->ci->db->platform(),
            'version' => $this->ci->db->version(),
        );
        return $info;
    }

    
}