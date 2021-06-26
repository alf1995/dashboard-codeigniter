<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/DashboardController.php';

class Modulo extends DashboardController {
    private $resultado;
    private $items = array();

    public function __construct() {
        parent::__construct();

        $this->load->library(array('dashboard/auth'));
        $this->load->helper(array());
        $this->load->model(array('m_roles_modulo','m_roles_accion'));
    
        $this->items['miModulo'] = 'modulo';
        $this->items['carpetaProyecto'] = config_admin()['directorySystem'];
        $this->items['baseUrl'] = base_url();
        $this->items['getUrl'] = base_url().$this->items['carpetaProyecto'].'/';
        $this->accesoSession = sessiones_helper::obtieneInfoSesion('sesionUsuario');
        $this->verifica_acceso = $this->auth->verifica_acceso();
        if($this->accesoSession->accesoTmpNivel != 500){
            echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
        }
    }

    public function listar($pagina = 0){
        $data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - listar Módulo';

        $lista = 'Roles|fa fa-briefcase';
        $listaSub = 'Modulo|far fa-circle text-blue';
        $data['menuGenerado'] = $this->auth->crearMenu($lista, $listaSub);

        $condiciones = array();
        /* Paginación */
            $links = $this->items['getUrl'].'modulo/listar';
            $returns_pagination = $this->paginationCompress($links, $this->m_roles_modulo->contador(),4, 10,10,$this->uri->segment(4));
        /* Información */
            $modulo = $this->m_roles_modulo->mostrar_datos($condiciones,$returns_pagination["limite"],$returns_pagination["offset"]);
            if(!empty($modulo)){
                $this->table->set_template(
                    array(
                        'table_open' => '<table class="table table-bordered table-sm mb-0 dataTable" id="infoTable">',
                        'heading_cell_start'  => '<th style="vertical-align:middle; text-align:center;">',
                        'thead_open'  => '<thead class="thead-light">',
                        'cell_start' => '<td style="vertical-align:middle; text-align:center;">',
                        'cell_alt_start' => '<td style="vertical-align:middle; text-align:center;">'
                    )
                );
                $todosCheckInfo = '<a style="color: black;" href="javascript:;" class="removerInfoTotal" data-url="'.$this->items['miModulo'].'/proceso/removerTodo" data-response="respuesta" data-toggle="tooltip" title="ELIMINAR ITEMS"><i class="fa fa-trash"></i> ELIMINAR</a>';
                $todosCheck = '<input type="checkbox" name="checkTodo" id="checkTodo" />';
                $this->table->set_heading('#', 'MODULO', 'FECHA REGISTRO', 'ACCIÓN');
                $accion = array(
                    'entity' => $this->items['miModulo'],
                    'route' => $this->items['baseUrl'].$this->items['carpetaProyecto'], 
                    'option' => 'editar|denegar|permitir|remover', 
                    'response' => 'respuesta', 
                    'class' => 'procesarInfo'
                );
                $i = 1;
                foreach($modulo AS $items){
                    $fecha_registro = ($items->fecha_registro >= 1104537600) ? $this->complementos->obtenerFecha($items->fecha_registro,7) : '---';
                    $this->table->add_row(
                        $i, 
                        '<span>'.$items->nombre.'</span>', 
                        '<span>'.$fecha_registro.'</span>',  
                        $this->auth->formAccion($accion, $items->id, $items->eliminacion_logica)
                    );
                    $i++;
                }
                $data['generaTabla'] = $this->table->generate();
                $data['generaPaginacion'] = $this->pagination->create_links();
            }else{
                $data['generaTabla'] = '<h4>No se encontraron resultados</h4>';
            }

        $data = array_merge($data, $this->items);
        $data = array_merge($data, $this->verifica_acceso);
        $this->loadViews($this->items['carpetaProyecto'],$this->items['miModulo'] .'/'.'listar_modulo', $data);
    }

    public function agregar(){
        $data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - Agregar Módulo';
        
        $lista = 'Roles|fa fa-briefcase';
        $listaSub = 'Modulo|far fa-circle text-blue';
        $data['menuGenerado'] = $this->auth->crearMenu($lista, $listaSub);

        /* ########## */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $this->verifica_acceso);
        $this->loadViews($this->items['carpetaProyecto'],$this->items['miModulo'] .'/'.'agregar_modulo', $data);
    }

    public function editar($id = ''){
        $data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - Editar Módulo';

        $lista = 'Roles|fa fa-briefcase';
        $listaSub = 'Modulo|far fa-circle text-blue';
        $data['menuGenerado'] = $this->auth->crearMenu($lista, $listaSub);
        
        sessiones_helper::eliminaSesion('editarModulo');
        $condicion = array(
            'roles_modulo.id' => $id
        );
        $resultado = $this->m_roles_modulo->mostrar_datos($condicion);
        if(!empty($resultado)){
            $modulo = $resultado[0];
            sessiones_helper::creaSesion('editarModulo', $modulo->id);
            $data['editar_nombre'] = $modulo->nombre;
        } else {
            echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
        }
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $this->verifica_acceso);
        $this->loadViews($this->items['carpetaProyecto'],$this->items['miModulo'] .'/'.'editar_modulo', $data);
    }

    public function proceso($accion, $id = ''){
        switch($accion){
            case 'agregar':
                    $msjError = TRUE;
                    $nombre = $this->complementos->addSlashtag($this->input->post('nombre',TRUE));

                    $error = '';
                    $error .= valida_campo($nombre, 'not_empty|minlength:2|maxlength:60', 'Nombre');   
                    if($error != ''){ 
                        $message = sprintf(error_helper::msg()->msg201,$error);
                        echo alerta_error($message);EXIT; 
                    }
                    if($this->m_roles_modulo->existe_info('nombre', $nombre)){
                        $message = sprintf(error_helper::msg()->msg7, 'nombre '.$nombre);
                        echo alerta_error($message);EXIT; 
                    }

                    $columnaDatos = array(
                        'nombre' => mb_strtolower($nombre, 'UTF-8'),
                        'slug' => url_seo($nombre),
                        'fecha_registro' => time(),
                        'eliminacion_logica' => 1
                    );
                    $resultado = $this->m_roles_modulo->insertar($columnaDatos);
                    if(!empty($resultado)){
                        $message = sprintf(error_helper::msg()->msg502,$nombre);
                        $redUrl = $this->items['getUrl'].'modulo/listar';
                        echo alerta_exito($message,1,$redUrl);
                        EXIT();
                    }
                    if($msjError){
                        $message = sprintf(error_helper::msg()->msg6);
                        echo alerta_error($message);EXIT; 
                    }
                break;
            case 'editar':
                    $msjError = TRUE;
                    $moduloId = sessiones_helper::obtieneSesion('editarModulo');
                    $nombre = $this->complementos->addSlashtag($this->input->post('nombre',TRUE));

                    $error = '';
                    $error .= valida_campo($nombre, 'not_empty|minlength:2|maxlength:60', 'Nombre');

                    if($error != ''){ 
                        $message = sprintf(error_helper::msg()->msg201,$error);
                        echo alerta_error($message);EXIT; 
                    }

                    if($this->m_roles_modulo->existe_info('nombre', $nombre,$moduloId)){
                        $message = sprintf(error_helper::msg()->msg7, 'nombre '.$nombre);
                        echo alerta_error($message);EXIT; 
                    }

                    $condiciones = array( 'roles_modulo.id' => $moduloId );
                    $resultadoModulo = $this->m_roles_modulo->mostrar_datos($condiciones);

                    $columnaDatos = array(
                        'nombre' => mb_strtolower($nombre, 'UTF-8'),
                        'slug' => url_seo($nombre),
                        'fecha_modificacion' => time(),
                    );
                    $resultado = $this->m_roles_modulo->actualizar($columnaDatos, array( 'roles_modulo.id' => $moduloId ));
                    if(!empty($resultado)){
                        sessiones_helper::eliminaSesion('editarModulo');
                        $message = sprintf(error_helper::msg()->msg503,$nombre);
                        echo alerta_exito($message,3);
                        EXIT();
                    }
                    if($msjError){
                        $message = sprintf(error_helper::msg()->msg6);
                        echo alerta_error($message);EXIT; 
                    }
                break;
            case 'permitir':
                $msjError = TRUE;
                if($this->m_roles_modulo->existe($id)){
                    $condiciones = array(
                        'roles_modulo.eliminacion_logica' => 0,
                        'roles_modulo.id' => $id
                    );
                    $modulo = $this->m_roles_modulo->mostrar_datos($condiciones);
                    if(!empty($modulo)){
                        $this->m_roles_modulo->permitir_info($modulo[0]->id);
                        $message = sprintf(error_helper::msg()->msg201, 'Se activó el item exitosamente.');
                        echo alerta_exito($message,2);EXIT;
                    }
                }
                if($msjError){
                    $message = sprintf(error_helper::msg()->msg6);
                    echo alerta_error($message);EXIT; 
                }
                break;
            case 'denegar':
                $msjError = TRUE;
                if($this->m_roles_modulo->existe($id)){
                    $condiciones = array(
                        'roles_modulo.eliminacion_logica' => 1,
                        'roles_modulo.id' => $id
                    );
                    $modulo = $this->m_roles_modulo->mostrar_datos($condiciones);
                    if(!empty($modulo)){
                        $this->m_roles_modulo->denegar_info($modulo[0]->id);
                        $message = sprintf(error_helper::msg()->msg201, 'Se desactivó el item exitosamente.');
                        echo alerta_exito($message,2);EXIT;
                    }
                }
                if($msjError){
                    $message = sprintf(error_helper::msg()->msg6);
                    echo alerta_error($message);EXIT; 
                }
                break;
            case 'remover':
                $msjError = TRUE;
                if($this->m_roles_modulo->existe($id)){
                    $condiciones = array(
                        'roles_modulo.eliminacion_logica' => 0,
                        'roles_modulo.id' => $id
                    );
                    $modulo = $this->m_roles_modulo->mostrar_datos($condiciones);
                    if(!empty($modulo)){
                        $resultado = $this->m_roles_modulo->eliminar( array( 'roles_modulo.id' => $id ) );
                        if($resultado){
                            $message = sprintf(error_helper::msg()->msg201, 'La eliminación se completo exitosamente.');
                            echo alerta_exito($message,2);EXIT; 
                        }  
                    }
                }
                if($msjError){
                    $message = sprintf(error_helper::msg()->msg6);
                    echo alerta_error($message);EXIT; 
                }
                break;
            default:
                return FALSE;
        }
    }
    

}