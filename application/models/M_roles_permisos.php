<?php

require_once APPPATH.'libraries/Model_DB.php';

clASs M_roles_permisos extends Model_DB {
    
    public function __construct() {
        parent::__construct();
        parent::setTabla('roles_permisos');
        parent::setId('id');
    }
    
    public function get_items_table() {
        $this->ci->db->select(''
                . 'roles_permisos.id AS id, '
                . 'roles_permisos.usuario_id AS usuario_id, '
                . 'roles_permisos.modulo_id AS modulo_id, '
                . 'roles_permisos.acciones AS acciones, '
                . 'roles_permisos.fecha_registro AS fecha_registro, '
                . 'roles_permisos.fecha_modificacion AS fecha_modificacion, '
                . 'roles_permisos.eliminacion_logica AS eliminacion_logica, '
                . 'roles_modulo.id AS rolesModuloId, '
                . 'roles_modulo.nombre AS rolesModuloNombre, '
                . 'roles_modulo.eliminacion_logica AS rolesModuloEliminacion, '
                . 'usuario.id AS usuarioId, '
                . 'usuario.rol_id AS rolId, '
                . 'usuario.nombre AS usuarioNombre ');
        $this->ci->db->join('usuario', 'usuario.id = roles_permisos.usuario_id', 'inner');
        $this->ci->db->join('roles_modulo', 'roles_modulo.id = roles_permisos.modulo_id', 'inner');        
        $this->ci->db->from($this->tabla);
    }
    
}
