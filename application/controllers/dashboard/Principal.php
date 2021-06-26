<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/DashboardController.php';

class Principal extends DashboardController {
    
    private $resultado;
    private $items = array();
    
    public function __construct() {
        parent::__construct();

        $this->load->library(array('dashboard/auth'));
        $this->load->helper(array());
        $this->load->model(array());

        $this->items['carpetaProyecto'] = config_admin()['directorySystem'];
        $this->items['getUrl'] = base_url().$this->items['carpetaProyecto'].'/';
        $this->accesoSession = sessiones_helper::obtieneInfoSesion('sesionUsuario');
        $this->verifica_acceso = $this->auth->verifica_acceso();
    }
    
    public function panel(){
        $data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - Principal';
    
        $lista = 'Principal|fas fa-tachometer-alt';
        $data['menuGenerado'] = $this->auth->crearMenu($lista);

        $data = array_merge($data, $this->items);
        $data = array_merge($data, $this->verifica_acceso);
        $this->loadViews($this->items['carpetaProyecto'],'panel', $data);
    }  
}
