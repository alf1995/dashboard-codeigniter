<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/DashboardController.php';

class Usuario extends DashboardController {
    private $resultado;
    private $items = array();

    public function __construct() {
        parent::__construct();

        $this->load->library(array('dashboard/auth'));
        $this->load->helper(array());
        $this->load->model(array('m_usuario'));

        $this->items['miModulo'] = 'usuario';
        $this->items['carpetaProyecto'] = config_admin()['directorySystem'];
        $this->items['baseUrl'] = base_url();
        $this->items['getUrl'] = base_url().$this->items['carpetaProyecto'].'/';
        $this->accesoSession = sessiones_helper::obtieneInfoSesion('sesionUsuario');
        $this->verifica_acceso = $this->auth->verifica_acceso();
    }

    public function listar($pagina = 0){
        if(!$this->auth->seguridadAccionModulo($this->accesoSession->accesoTmpId,$this->accesoSession->accesoTmpNivel,$this->items['miModulo'],'listar')){
            echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
        }
        $data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - listar Usuario';

        $lista = 'Usuarios|fa fa-users';
        $listaSub = 'Listar|fa fa-list-alt text-blue';
        $data['menuGenerado'] = $this->auth->crearMenu($lista, $listaSub);

        $condiciones = array();
        /* Paginación */
            $links = $this->items['getUrl'].'usuario/listar';
            $returns_pagination = $this->paginationCompress($links, $this->m_usuario->contador(),4, 10,10,$this->uri->segment(4));
        /* Información */
            $usuario = $this->m_usuario->mostrar_datos($condiciones,$returns_pagination["limite"],$returns_pagination["offset"]);
            if(!empty($usuario)){
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
                $this->table->set_heading('#', 'USUARIO', 'NIVEL DE USUARIO', 'NOMBRE(S) & APELLIDO(S)', $todosCheckInfo.'<br />'.$todosCheck, 'ACCIÓN');
                $accion = array(
                    'entity' => $this->items['miModulo'],
                    'route' => $this->items['baseUrl'].$this->items['carpetaProyecto'], 
                    'option' => 'observar|editar|denegar|permitir|remover', 
                    'response' => 'respuesta', 
                    'class' => 'procesarInfo'
                );
                $i = 1;
                foreach($usuario AS $items){
                    $checkAccion = '<input type="checkbox" name="checkRemover" id="checkRemover" value="'.$items->id.'" />'; 
                    $fechaRegistro = ($items->fecha_registro >= 1104537600) ? $this->complementos->obtenerFecha($items->fecha_registro,7) : '---';
                    $this->table->add_row(
                        $i, 
                        '<span>'.$items->correo.'</span>', 
                        '<span>'.$fechaRegistro.'</span>', 
                        '<span>'.$items->nombre.' '.$items->apellido.'</span>',  
                        '', 
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
        $this->loadViews($this->items['carpetaProyecto'],$this->items['miModulo'] .'/'.'listar_usuario', $data);
    }

    public function agregar(){
        if(!$this->auth->seguridadAccionModulo($this->accesoSession->accesoTmpId,$this->accesoSession->accesoTmpNivel,$this->items['miModulo'],'agregar')){
            echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
        }
        $data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - Agregar Usuario';

        $lista = 'Usuarios|fa fa-users';
        $listaSub = 'Agregar|fa fa-user-plus text-blue';
        $data['menuGenerado'] = $this->auth->crearMenu($lista, $listaSub);

        /* ########## */
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $this->verifica_acceso);
        $this->loadViews($this->items['carpetaProyecto'],$this->items['miModulo'] .'/'.'agregar_usuario', $data);
    }

    public function editar($id = ''){
        if(!$this->auth->seguridadAccionModulo($this->accesoSession->accesoTmpId,$this->accesoSession->accesoTmpNivel,$this->items['miModulo'],'editar')){
            echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
        }
        $data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - Editar Usuario';

        $lista = 'Usuarios|fa fa-users';
        $listaSub = 'Listar|fa fa-list-alt text-blue';
        $data['menuGenerado'] = $this->auth->crearMenu($lista, $listaSub);

        sessiones_helper::eliminaSesion('editarUsuario');
        $condicion = array(
            'usuario.id' => $id,
        );
        $resultado = $this->m_usuario->mostrar_datos($condicion);
        if(!empty($resultado)){
            $usuario = $resultado[0];
            sessiones_helper::creaSesion('editarUsuario', $usuario->id);
            $data['prefil_usuario'] = $usuario->correo;
            $data['prefil_nombre'] = $usuario->nombre;
            $data['prefil_apellido'] = $usuario->apellido;
        } else {
            echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
        }
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $this->verifica_acceso);
        $this->loadViews($this->items['carpetaProyecto'],$this->items['miModulo'] .'/'.'editar_usuario', $data);
    }


    public function perfil(){
        $data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - Perfil Usuario';

        $lista = 'Usuarios|fa fa-users';
        $listaSub = 'Listar|fa fa-list-alt text-blue';
        $data['menuGenerado'] = $this->auth->crearMenu($lista, $listaSub);

        $condiciones = array(
            'usuario.id' => $this->accesoSession->accesoTmpId
        );
        $resultado = $this->m_usuario->mostrar_datos($condiciones);
        if(!empty($resultado)){
            $usuario = $resultado[0];
            $data['prefil_usuario'] = $usuario->correo;
            $data['prefil_nombre'] = $usuario->nombre;
            $data['prefil_apellido'] = $usuario->apellido;
        }
        
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $this->verifica_acceso);
        $this->loadViews($this->items['carpetaProyecto'],$this->items['miModulo'] .'/'.'perfil_usuario', $data);
    }

    public function observar($id = ''){
        if(!$this->auth->seguridadAccionModulo($this->accesoSession->accesoTmpId,$this->accesoSession->accesoTmpNivel,$this->items['miModulo'],'observar')){
            echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
        }
        $data['titulo_pagina'] = config_admin()['sisInfoTituloEmpresa'].' - Observar Usuario';

        $lista = 'Usuarios|fa fa-users';
        $listaSub = 'Listar|fa fa-list-alt text-blue';
        $data['menuGenerado'] = $this->auth->crearMenu($lista, $listaSub);

        $condiciones = array(
            'usuario.id' => $id
        );
        $resultado = $this->m_usuario->mostrar_datos($condiciones);
        if(!empty($resultado)){
            $usuario = $resultado[0];
            $image = ($usuario->imagen != '') ? $usuario->imagen : 'empty.jpg';
            if($usuario->rol_id == 500){
                $rolTitulo = 'Administrador';
            } else {
                $rolTitulo = 'Usuario';
            }
            $data['observar_id'] = $usuario->id;
            $data['observar_usuario'] = $usuario->correo;
            $data['observar_nombre'] = $usuario->nombre;
            $data['observar_apellido'] = $usuario->apellido;
            $data['observar_nivel'] = $rolTitulo;
            $data['observar_imagen'] = base_url().'crop/150/150/usuario-'.$image;
            $data['btn_editar'] = base_url().'dashboard/usuario/editar/'.$usuario->id;
            $data['fechaRegistro'] = ($usuario->fecha_registro >= 1104537600) ? $this->complementos->obtenerFecha($usuario->fecha_registro,7) : '---';
            $data['fechaModificacion'] = ($usuario->fecha_modificacion >= 1104537600) ? $this->complementos->obtenerFecha($usuario->fecha_modificacion,7) : '---';
        }
        
        $data = array_merge($data, $this->items);
        $data = array_merge($data, $this->verifica_acceso);
        $this->loadViews($this->items['carpetaProyecto'],$this->items['miModulo'] .'/'.'observar_usuario', $data);
    }

    public function proceso($accion, $id = ''){
        switch($accion){
            case 'agregar':
                if(!$this->auth->seguridadAccionModulo($this->accesoSession->accesoTmpId,$this->accesoSession->accesoTmpNivel,$this->items['miModulo'],'agregar')){
                    echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
                }
                    $msjError = TRUE;
                    $usuario = $this->complementos->addSlashtag($this->input->post('usuario',TRUE));
                    $nombre = $this->complementos->addSlashtag($this->input->post('nombre',TRUE));
                    $apellido = $this->complementos->addSlashtag($this->input->post('apellido',TRUE));
                    $clave = $this->complementos->addSlashtag($this->input->post('clave',TRUE));
                    $reclave = $this->complementos->addSlashtag($this->input->post('reclave',TRUE));
                    $rolId = 201;
                    $imagenPrincipal = $this->complementos->obtenerArchivo('imagenPrincipal', 'unico');

                    $error = '';
                    $error .= valida_campo($usuario, 'not_empty|email|minlength:5|maxlength:80', 'Usuario');
                    $error .= valida_campo($nombre, 'not_empty|minlength:2|maxlength:50', 'Nombre');
                    $error .= valida_campo($apellido, 'not_empty|minlength:2|maxlength:50', 'Apellido');
                    $error .= valida_campo($rolId, 'not_empty', 'Roles');
                    $error .= valida_campo($clave, 'not_empty|minlength:5|maxlength:30', 'Contraseña');    
                    if(!empty($clave)){
                        $error .= valida_campo($reclave, 'not_empty|minlength:5|maxlength:30', 'Verificar contraseña');
                        $error .= valida_campo($clave, 'password::'.$reclave, 'Contraseña');
                    } 
                    if(!empty($imagenPrincipal)){
                        $error .= valida_imagen($imagenPrincipal, 'maxwidth:500|maxheight:500:|maxsize:1', 'Imagen');
                    }
                    if($error != ''){ 
                        $message = sprintf(error_helper::msg()->msg201,$error);
                        echo alerta_error($message);EXIT; 
                    }
                    if($this->m_usuario->existe_info('correo', $usuario)){
                        $message = sprintf(error_helper::msg()->msg7, 'usuario '.$usuario);
                        echo alerta_error($message);EXIT; 
                    }
                    if(!empty($imagenPrincipal)){
                        $opciones = array(
                            'marcaTipo' => FALSE,
                            'marcaInfo' => FALSE,
                            'ajuste' => 'w',
                            'ajusteImagen' => '',
                            'desenfocado' => FALSE,
                            'cantidadCopia' => array()
                        );
                        $obtieneImagen = $this->complementos->almacenarImagen($imagenPrincipal, 'public/uploads/usuario', $opciones, TRUE);
                    } else {
                        $obtieneImagen = '';
                    }

                    $claveFinal = password_hash($reclave, PASSWORD_DEFAULT);
                    $columnaDatos = array(
                        'correo' => $usuario,
                        'clave' => $claveFinal,
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'imagen' => $obtieneImagen,
                        'fecha_registro' => time(),
                        'rol_id' => $rolId,
                        'eliminacion_logica' => 1
                    );
                    $resultado = $this->m_usuario->insertar($columnaDatos);
                    if(!empty($resultado)){
                        $message = sprintf(error_helper::msg()->msg502,$usuario);
                        $redUrl = $this->items['getUrl'].'usuario/listar';
                        echo alerta_exito($message,3,$redUrl);
                        EXIT();
                    }
                    if($msjError){
                        $message = sprintf(error_helper::msg()->msg6);
                        echo alerta_error($message);EXIT; 
                    }
                break;
            case 'editar':
                if(!$this->auth->seguridadAccionModulo($this->accesoSession->accesoTmpId,$this->accesoSession->accesoTmpNivel,$this->items['miModulo'],'editar')){
                    echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
                }
                    $msjError = TRUE;
                    $usuarioId = sessiones_helper::obtieneSesion('editarUsuario');
                    $usuario = $this->complementos->addSlashtag($this->input->post('usuario',TRUE));
                    $nombre = $this->complementos->addSlashtag($this->input->post('nombre',TRUE));
                    $apellido = $this->complementos->addSlashtag($this->input->post('apellido',TRUE));
                    $clave = $this->complementos->addSlashtag($this->input->post('clave',TRUE));
                    $reclave = $this->complementos->addSlashtag($this->input->post('reclave',TRUE));
                    $imagenPrincipal = $this->complementos->obtenerArchivo('imagenPrincipal', 'unico');

                    $error = '';
                    $error .= valida_campo($usuario, 'not_empty|email|minlength:5|maxlength:80', 'Usuario');
                    $error .= valida_campo($nombre, 'not_empty|minlength:2|maxlength:50', 'Nombre');
                    $error .= valida_campo($apellido, 'not_empty|minlength:2|maxlength:50', 'Apellido');
                    if(!empty($clave)){
                        $error .= valida_campo($clave, 'minlength:5|maxlength:30', 'Nueva contraseña');
                        $error .= valida_campo($reclave, 'not_empty|minlength:5|maxlength:30', 'Verificar contraseña');
                        $error .= valida_campo($clave, 'password::'.$reclave, 'Contraseña');
                    } 
                    if(!empty($imagenPrincipal)){
                        $error .= valida_imagen($imagenPrincipal, 'maxwidth:500|maxheight:500:|maxsize:1', 'Imagen');
                    }

                    if($error != ''){ 
                        $message = sprintf(error_helper::msg()->msg201,$error);
                        echo alerta_error($message);EXIT; 
                    }

                    if($this->m_usuario->existe_info('correo', $usuario,$usuarioId)){
                        $message = sprintf(error_helper::msg()->msg7, 'usuario '.$usuario);
                        echo alerta_error($message);EXIT; 
                    }

                    $condiciones = array( 'usuario.id' => $usuarioId );
                    $resultadoUsuario = $this->m_usuario->mostrar_datos($condiciones);

                    if(!empty($imagenPrincipal)){
                        $opciones = array(
                            'marcaTipo' => FALSE,
                            'marcaInfo' => FALSE,
                            'ajuste' => 'w',
                            'ajusteImagen' => '',
                            'desenfocado' => FALSE,
                            'cantidadCopia' => array()
                        );
                        $obtieneImagen = $this->complementos->almacenarImagen($imagenPrincipal, 'public/uploads/usuario', $opciones, TRUE);
                    } else {
                        $obtieneImagen = $resultadoUsuario[0]->imagen;
                    }
                    $claveEncryptada = password_hash($reclave, PASSWORD_DEFAULT);
                    $claveFinal = (!empty($reclave)) ? $claveEncryptada : $resultadoUsuario[0]->clave ;

                    $columnaDatos = array(
                        'correo' => $usuario,
                        'clave' => $claveFinal,
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'imagen' => $obtieneImagen,
                        'fecha_modificacion' => time(),
                    );
                    $resultado = $this->m_usuario->actualizar($columnaDatos, array( 'usuario.id' => $usuarioId ));
                    if(!empty($resultado)){
                        sessiones_helper::eliminaSesion('editarUsuario');
                        $message = sprintf(error_helper::msg()->msg503,$usuario);
                        echo alerta_exito($message,3);
                        EXIT();
                    }
                    if($msjError){
                        $message = sprintf(error_helper::msg()->msg6);
                        echo alerta_error($message);EXIT; 
                    }
                break;
            case 'perfil':
                    $msjError = TRUE;
                    $usuarioId = $this->accesoSession->accesoTmpId;
                    $usuario = $this->complementos->addSlashtag($this->input->post('usuario',TRUE));
                    $nombre = $this->complementos->addSlashtag($this->input->post('nombre',TRUE));
                    $apellido = $this->complementos->addSlashtag($this->input->post('apellido',TRUE));
                    $clave = $this->complementos->addSlashtag($this->input->post('clave',TRUE));
                    $reclave = $this->complementos->addSlashtag($this->input->post('reclave',TRUE));
                    $imagenPrincipal = $this->complementos->obtenerArchivo('imagenPrincipal', 'unico');

                    $error = '';
                    $error .= valida_campo($usuario, 'not_empty|email|minlength:5|maxlength:80', 'Usuario');
                    $error .= valida_campo($nombre, 'not_empty|minlength:2|maxlength:50', 'Nombre');
                    $error .= valida_campo($apellido, 'not_empty|minlength:2|maxlength:50', 'Apellido');
                    if(!empty($clave)){
                        $error .= valida_campo($clave, 'minlength:5|maxlength:30', 'Nueva contraseña');
                        $error .= valida_campo($reclave, 'not_empty|minlength:5|maxlength:30', 'Verificar contraseña');
                        $error .= valida_campo($clave, 'password::'.$reclave, 'Contraseña');
                    } 
                    if(!empty($imagenPrincipal)){
                        $error .= valida_imagen($imagenPrincipal, 'maxwidth:500|maxheight:500:|maxsize:1', 'Imagen');
                    }
                    if($error != ''){ 
                        $message = sprintf(error_helper::msg()->msg201,$error);
                        echo alerta_error($message);EXIT; 
                    }
                    $condiciones = array(
                        'usuario.id' => $usuarioId
                    );
                    $resultadoUsuario = $this->m_usuario->mostrar_datos($condiciones);
                    if(!empty($imagenPrincipal)){
                        $opciones = array(
                            'marcaTipo' => FALSE,
                            'marcaInfo' => FALSE,
                            'ajuste' => 'w',
                            'ajusteImagen' => '',
                            'desenfocado' => FALSE,
                            'cantidadCopia' => array()
                        );
                        $obtieneImagen = $this->complementos->almacenarImagen($imagenPrincipal, 'public/uploads/usuario', $opciones, TRUE);
                    } else {
                        $obtieneImagen = $resultadoUsuario[0]->imagen;
                    }

                    $claveEncryptada = password_hash($reclave, PASSWORD_DEFAULT);
                    $claveFinal = (!empty($reclave)) ? $claveEncryptada : $resultadoUsuario[0]->clave ;
                    $columnaDatos = array(
                        'correo' => $usuario,
                        'clave' => $claveFinal,
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'imagen' => $obtieneImagen,
                        'fecha_modificacion' => time(),
                    );
                    $resultado = $this->m_usuario->actualizar($columnaDatos, array( 'usuario.id' => $usuarioId ));
                    if(!empty($resultado)){
                        $message = sprintf(error_helper::msg()->msg503,$usuario);
                        echo alerta_exito($message,3);
                        EXIT();
                    }
                    if($msjError){
                        $message = sprintf(error_helper::msg()->msg6);
                        echo alerta_error($message);EXIT; 
                    }
                break;
            case 'permitir':
                if(!$this->auth->seguridadAccionModulo($this->accesoSession->accesoTmpId,$this->accesoSession->accesoTmpNivel,$this->items['miModulo'],'permitir')){
                    echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
                }
                $msjError = TRUE;
                if($this->m_usuario->existe($id)){
                    $condiciones = array(
                        'usuario.eliminacion_logica' => 0,
                        'usuario.id' => $id
                    );
                    $usuario = $this->m_usuario->mostrar_datos($condiciones);
                    if(!empty($usuario)){
                        $this->m_usuario->permitir_info($usuario[0]->id);
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
                if(!$this->auth->seguridadAccionModulo($this->accesoSession->accesoTmpId,$this->accesoSession->accesoTmpNivel,$this->items['miModulo'],'denegar')){
                    echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
                }
                $msjError = TRUE;
                if($this->m_usuario->existe($id)){
                    $condiciones = array(
                        'usuario.eliminacion_logica' => 1,
                        'usuario.rol_id !=' => 500,
                        'usuario.id' => $id
                    );
                    $usuario = $this->m_usuario->mostrar_datos($condiciones);
                    if(!empty($usuario)){
                        $this->m_usuario->denegar_info($usuario[0]->id);
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
                if(!$this->auth->seguridadAccionModulo($this->accesoSession->accesoTmpId,$this->accesoSession->accesoTmpNivel,$this->items['miModulo'],'remover')){
                    echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
                }
                $msjError = TRUE;
                if($this->m_usuario->existe($id)){
                    $condiciones = array(
                        'usuario.eliminacion_logica' => 0,
                        'usuario.id' => $id
                    );
                    $usuario = $this->m_usuario->mostrar_datos($condiciones);
                    if(!empty($usuario)){
                        if($this->m_logs->existe_info('usuario_id', $usuario[0]->id)){
                            $message = sprintf(error_helper::msg()->msg201, 'Es imposible eliminar al usuario.');
                            echo alerta_error($message);EXIT; 
                        } else{
                            $resultado = $this->m_usuario->eliminar( array( 'usuario.id' => $usuario[0]->id ) );
                            if($resultado){
                                $this->complementos->eliminarArchivo($usuario[0]->imagen, 'public/uploads/usuario');
                                $message = sprintf(error_helper::msg()->msg201, 'La eliminación se completo exitosamente.');
                                echo alerta_exito($message,2);EXIT; 
                            }
                        }
                    }
                }
                if($msjError){
                    $message = sprintf(error_helper::msg()->msg6);
                    echo alerta_error($message);EXIT; 
                }
                break;
            case 'removerTodo':
                if(!$this->auth->seguridadAccionModulo($this->accesoSession->accesoTmpId,$this->accesoSession->accesoTmpNivel,$this->items['miModulo'],'remover')){
                    echo re_direccion($this->items['getUrl'].'principal/panel'); EXIT;
                }
                $msjError = TRUE;
                $checkRemover = $this->input->post('checkRemover');
                if(!empty($checkRemover)){
                    foreach($checkRemover as $items){
                        if($this->m_usuario->existe($items['value'])){
                            $condiciones = array(
                                'usuario.eliminacion_logica' => 0,
                                'usuario.id' => $items['value']
                            );
                            $usuario = $this->m_usuario->mostrar_datos($condiciones);
                            if(!empty($usuario)){
                                $resultado = $this->m_usuario->eliminar( array( 'usuario.id' => $usuario[0]->id ) );
                                if($resultado){
                                    $this->complementos->eliminarArchivo($usuario[0]->imagen, 'public/uploads/usuario');
                                } else{
                                    continue;
                                }  
                            } else{
                                continue;
                            }
                        } else{
                            continue;
                        }
                    }
                    $message = sprintf(error_helper::msg()->msg201, 'Se eliminaron correctamente los elementos seleccionados.');
                    echo alerta_exito($message);EXIT; 
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