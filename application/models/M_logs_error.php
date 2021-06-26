<?php

require_once APPPATH.'libraries/Model_DB.php';

class M_logs_error extends Model_DB {

        public function __construct() {
            parent::__construct();
            parent::setTabla('logs_error');
            parent::setId('id');
        }

        public function get_items_table()
        {
            $this->ci->db->select('*');
            $this->ci->db->from($this->tabla);
        }

}
