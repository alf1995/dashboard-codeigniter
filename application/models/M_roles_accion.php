<?php

require_once APPPATH.'libraries/Model_DB.php';

clASs M_roles_accion extends Model_DB {
    
    public function __construct() {
        parent::__construct();
        parent::setTabla('roles_accion');
        parent::setId('id');
    }
    
    public function get_items_table() {
        $this->ci->db->select('*');
        $this->ci->db->from($this->tabla);
    }
}
