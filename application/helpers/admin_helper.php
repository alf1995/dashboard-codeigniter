<?php

if (!function_exists('config_admin')) {
     function config_admin() {
        $ci =& get_instance();

        $librerias = array();
        $helper = array();
        $modelos = array();
        $ci->load->library($librerias);
        $ci->load->helper($helper);
        $ci->load->model($modelos);
        $ci->config->load('admin_config',TRUE,TRUE);
        
        /* CONFIGURACION GENERAL DEL INSTALACION */
        $items['insIntentoError'] = $ci->config->item('default_error_system','admin_config');
        $items['insTiempoBloqueo'] = $ci->config->item('default_block_system','admin_config');
        $items['insDuracionCaptcha'] = $ci->config->item('default_captcha_system','admin_config');
        /* CONFIGURACION GENERAL DEL SISTEMA */
        $items['sisInfoTituloEmpresa'] = $ci->config->item('default_title_system','admin_config');

        $items['sisPieDePagina'] = $ci->config->item('default_footer_system','admin_config');
        $items['sisWebDePagina'] = $ci->config->item('default_web_system','admin_config');

        $items['sisVersion'] = $ci->config->item('core_signature','admin_config');
        $items['sisDate'] = $ci->config->item('default_date','admin_config');

        /* CONFIGURACION GENERAL DEL DIRECTORIO */
        $items['directoryWeb'] = $ci->config->item('default_directory_web','admin_config');
        $items['directorySystem'] = $ci->config->item('default_directory_system','admin_config');
        /* CONFIGURACION MODULOS */
        $items['sisModulo'] = $ci->config->item('default_modulo','admin_config');
        $items['sisAccion'] = $ci->config->item('default_accion','admin_config');
        return $items;
    }
}
