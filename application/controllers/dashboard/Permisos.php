<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/DashboardController.php';

class Permisos extends DashboardController {
    private $resultado;
    private $items = array();

    public function __construct() {
        parent::__construct();

        $this->load->library(array('dashboard/auth'));
        $this->load->helper(array());
        $this->load->model(array('m_roles_permisos','m_roles_accion','m_roles_modulo'));
    
        $this->items['miModulo'] = 'permisos';
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
        $data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - listar Permisos';

        $lista = 'Roles|fa fa-briefcase';
        $listaSub = 'Permisos|far fa-circle text-blue';
        $data['menuGenerado'] = $this->auth->crearMenu($lista, $listaSub);

        $condiciones = array();
        /* Paginación */
            $links = $this->items['getUrl'].'permisos/listar';
            $returns_pagination = $this->paginationCompress($links, $this->m_roles_permisos->contador(),4, 10,10,$this->uri->segment(4));
        /* Información */
            $modulo = $this->m_roles_permisos->mostrar_datos($condiciones,$returns_pagination["limite"],$returns_pagination["offset"]);
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
                $this->table->set_heading('#','USUARIO', 'MODULO', 'FECHA REGISTRO', 'ACCIÓN');
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
                        '<span>'.$items->usuarioNombre.'</span>', 
                        '<span>'.$items->rolesModuloNombre.'</span>',  
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
        $this->loadViews($this->items['carpetaProyecto'],$this->items['miModulo'] .'/'.'listar_permisos', $data);
    }

    public function agregar(){
        $data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - Agregar Permisos';

        $lista = 'Roles|fa fa-briefcase';
        $listaSub = 'Permisos|far fa-circle text-blue';
        $data['menuGenerado'] = $this->auth->crearMenu($lista, $listaSub);

        $condicionUsuario = array(
            'usuario.rol_id !=' => 500
        );
        $obtieneUsuarios = $this->m_usuario->mostrar_datos($condicionUsuario);
        $resultado = [];
        foreach($obtieneUsuarios as $items){
            $resultado[$items->id] = mb_strtoupper($items->nombre, 'UTF-8');
        }
        $data['listadoUsuarios'] = $this->complementos->generaDesplegable($resultado, 'listadoUsuarios', '', 'Seleccione una opción', 'search', '', 'select');
        unset($resultado);

        $obtieneModulos = $this->m_roles_modulo->mostrar_datos();
        $resultado = [];
        foreach($obtieneModulos as $items){
            $resultado[$items->id] = mb_strtoupper($items->nombre, 'UTF-8');
        }
        $data['listadoModulos'] = $this->complementos->generaDesplegable($resultado, 'listadoModulos', '', 'Seleccione una opción', 'search', '', 'select');
        unset($resultado);

        $obtieneAcciones = $this->m_roles_accion->mostrar_datos();
        foreach($obtieneAcciones as $items){
            $resultado[$items->id] = mb_strtoupper($items->nombre, 'UTF-8');
        }
        $data['listadoAcciones'] = $this->complementos->generaDesplegable($resultado, 'listadoAcciones[]', -1, 'Seleccione una opción', 'search', '', 'multiple');
        unset($resultado);
        
        /* ########## */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $this->verifica_acceso);
        $this->loadViews($this->items['carpetaProyecto'],$this->items['miModulo'] .'/'.'agregar_permisos', $data);
    }

    public function editar($id = ''){
        $data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - Editar Permisos';

        $lista = 'Roles|fa fa-briefcase';
        $listaSub = 'Permisos|far fa-circle text-blue';
        $data['menuGenerado'] = $this->auth->crearMenu($lista, $listaSub);
        
        sessiones_helper::eliminaSesion('editarPermisos');
        $condicion = array(
            'roles_permisos.id' => $id
        );
        $resultado = $this->m_roles_permisos->mostrar_datos($condicion);
        if(!empty($resultado)){
            $permisos = $resultado[0];
            sessiones_helper::creaSesion('editarPermisos', $permisos->id);

            /* ##### */
            $obtieneUsuarios = $this->m_usuario->mostrar_datos();
            foreach($obtieneUsuarios as $items){
                $resultadoUsuario[$items->id] = mb_strtoupper($items->nombre, 'UTF-8');
            }
            $data['listadoUsuarios'] = $this->complementos->generaDesplegable($resultadoUsuario, 'listadoUsuarios', $permisos->usuario_id, 'Seleccione una opción', 'search', 'disabled', 'select');
            unset($resultadoUsuario);
            /* ##### */
            $obtieneModulos = $this->m_roles_modulo->mostrar_datos();
            foreach($obtieneModulos as $items){
                $resultadoModulo[$items->id] = mb_strtoupper($items->nombre, 'UTF-8');
            }
            $data['listadoModulos'] = $this->complementos->generaDesplegable($resultadoModulo, 'listadoModulos',$permisos->modulo_id, 'Seleccione una opción', 'search', 'disabled', 'select');
            unset($resultadoModulo);
            /* ##### */
            $accion_array = json_decode($permisos->acciones,TRUE);
            if($accion_array === null){
                $accion_array = -1;
            }
            /* ##### */
            $obtieneAcciones = $this->m_roles_accion->mostrar_datos();
            foreach($obtieneAcciones as $items){
                $resultadoDesplegable[$items->id] = mb_strtoupper($items->nombre, 'UTF-8');
            }
            $data['listadoAcciones'] = $this->complementos->generaDesplegable($resultadoDesplegable, 'listadoAcciones[]', $accion_array, 'Seleccione una opción', 'search', '', 'multiple');
            unset($resultadoDesplegable);
        } else {
            echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
        }
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $this->verifica_acceso);
        $this->loadViews($this->items['carpetaProyecto'],$this->items['miModulo'] .'/'.'editar_permisos', $data);
    }

    public function proceso($accion, $id = ''){
        switch($accion){
            case 'agregar':
                    $msjError = TRUE;
                    $usuario_id = $this->complementos->addSlashtag($this->input->post('listadoUsuarios',TRUE));
                    $modulo_id = $this->complementos->addSlashtag($this->input->post('listadoModulos',TRUE));
                    $acciones = $this->input->post('listadoAcciones',TRUE);

                    $error = '';
                    $error .= valida_campo($usuario_id, 'not_empty|minlength:1|maxlength:11|numeric', 'Usuario');   
                    $error .= valida_campo($modulo_id, 'not_empty|minlength:1|maxlength:11|numeric', 'Módulo');
                    if($error != ''){ 
                        $message = sprintf(error_helper::msg()->msg201,$error);
                        echo alerta_error($message);EXIT; 
                    }

                    $condiciones = array( 'roles_permisos.usuario_id' => $usuario_id,'roles_permisos.modulo_id' => $modulo_id );
                    $resultadoPermisos = $this->m_roles_permisos->mostrar_datos($condiciones);
                    if(!empty($resultadoPermisos)){
                        $message = sprintf('Ya existe los permisos que quiere agregar');
                        echo alerta_error($message);EXIT; 
                    }

                    $jsonEncode = json_encode($acciones, JSON_NUMERIC_CHECK);
                    $columnaDatos = array(
                        'usuario_id' => $usuario_id,
                        'modulo_id' => $modulo_id,
                        'acciones' => $jsonEncode,
                        'fecha_registro' => time(),
                        'eliminacion_logica' => 1,
                    );
                    $resultado = $this->m_roles_permisos->insertar($columnaDatos);
                    if(!empty($resultado)){
                        $message = sprintf(error_helper::msg()->msg502,'permiso');
                        $redUrl = $this->items['getUrl'].'permisos/listar';
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
                    $permisosId = sessiones_helper::obtieneSesion('editarPermisos');
                    //$usuario_id = $this->complementos->addSlashtag($this->input->post('listadoUsuarios',TRUE));
                    //$modulo_id = $this->complementos->addSlashtag($this->input->post('listadoModulos',TRUE));
                    $acciones = $this->input->post('listadoAcciones',TRUE);

                    $error = '';
                    //$error .= valida_campo($usuario_id, 'not_empty|minlength:1|maxlength:11|numeric', 'Usuario');   
                    //$error .= valida_campo($modulo_id, 'not_empty|minlength:1|maxlength:11|numeric', 'Módulo');
                    if($error != ''){ 
                        $message = sprintf(error_helper::msg()->msg201,$error);
                        echo alerta_error($message);EXIT; 
                    }

                    $jsonEncode = json_encode($acciones, JSON_NUMERIC_CHECK);
                    $columnaDatos = array(
                        //'usuario_id' => $usuario_id,
                        //'modulo_id' => $modulo_id,
                        'acciones' => $jsonEncode
                    );
                    $resultado = $this->m_roles_permisos->actualizar($columnaDatos, array( 'roles_permisos.id' => $permisosId ));
                    if(!empty($resultado)){
                        sessiones_helper::eliminaSesion('editarPermisos');
                        $message = sprintf(error_helper::msg()->msg503,'permiso');
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
                if($this->m_roles_permisos->existe($id)){
                    $condiciones = array(
                        'roles_permisos.eliminacion_logica' => 0,
                        'roles_permisos.id' => $id
                    );
                    $modulo = $this->m_roles_permisos->mostrar_datos($condiciones);
                    if(!empty($modulo)){
                        $this->m_roles_permisos->permitir_info($modulo[0]->id);
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
                if($this->m_roles_permisos->existe($id)){
                    $condiciones = array(
                        'roles_permisos.eliminacion_logica' => 1,
                        'roles_permisos.id' => $id
                    );
                    $modulo = $this->m_roles_permisos->mostrar_datos($condiciones);
                    if(!empty($modulo)){
                        $this->m_roles_permisos->denegar_info($modulo[0]->id);
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
                if($this->m_roles_permisos->existe($id)){
                    $condiciones = array(
                        'roles_permisos.eliminacion_logica' => 0,
                        'roles_permisos.id' => $id
                    );
                    $modulo = $this->m_roles_permisos->mostrar_datos($condiciones);
                    if(!empty($modulo)){
                        $resultado = $this->m_roles_permisos->eliminar( array( 'roles_permisos.id' => $id ) );
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