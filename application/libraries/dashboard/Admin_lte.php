<?php

class Admin_lte {
        
    public function cargarInfo($listarArg, $numeroArg){
        $tmp = array();
        for($i=0; $i<$numeroArg; $i++){
            $tmp[] = $listarArg[$i];
        }
        return $tmp;
    }
    
    public function filtrarPermiso($listaSeguridad, $vinculoTexto){
        if(is_array($listaSeguridad)){
            if(strpos($vinculoTexto, $listaSeguridad['moduloUsuario']) !== FALSE){ 
                foreach($listaSeguridad['accionesUsuario'] as $k => $v){
                    if(strpos($vinculoTexto, $k)){
                        switch($v){
                            case 0:
                                return FALSE;
                            case 1:
                                return TRUE;
                        }
                    }
                }
            }
        }
    }
    
    public function filtrarPermisoMenu($filtrarMenu, $listaSeguridad){
        $tmp = array();
        if(is_array($filtrarMenu)){
            foreach($filtrarMenu as $k => $v){
                if(is_array($v)){
                    $tmp[$k] = $this->filtrarPermisoMenu($v, $listaSeguridad);
                } else{
                    for($i=0; $i<count($listaSeguridad); $i++){  
                        if( (is_string($v)) && ($this->filtrarPermiso($listaSeguridad[$i], $v) === FALSE) ){
                            unset($tmp[$k]); break;
                        } else{
                            $tmp[$k] = $filtrarMenu[$k];
                        }
                    }
                }
            }
        }
        $tmp_1 = array();
        foreach($tmp as $k => $v){ 
            if(count($tmp) > 0){
                $tmp_1[$k] = $v;
            } else{
                continue;
            }
        }
        return $tmp_1;
    }
    
    public function filtrarMenu($menuOriginal, $cargarInfo){
        if(is_array($menuOriginal)){
            foreach($menuOriginal as $k => $v){
                if(is_int($k)){
                    $tmp[$k] = $menuOriginal[$k];
                } else
                if(array_keys($cargarInfo, $k)){
                    $tmp[$k.'|active'] = $this->filtrarMenu($menuOriginal[$k], $cargarInfo);
                } else{
                    $tmp[$k] = $menuOriginal[$k];
                }
            }
        } else{
            $tmp = $menuOriginal;
        }
        return $tmp;
    }
    
    private $conteoCabecera = 1;
    
    public function generarMenu($obtieneMenu){
        if(is_array($obtieneMenu)){
            if($this->conteoCabecera == 1){
                $plantillaMenu = '<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">';
                $this->conteoCabecera = 0;
            } else{
                $plantillaMenu = '<ul class="nav nav-treeview">';
            }
            foreach($obtieneMenu as $k => $v){
                $expDatos = explode('|', $k);
                $verificaActivo = (count($expDatos) >= 3) ? 'active' : '';
                $verificaActivoMenu = (count($expDatos) >= 3) ? 'menu-is-opening menu-open' : '';
                if(is_array($v)){
                    $plantillaMenu .= '<li class="nav-item '.$verificaActivoMenu.'">';
                    $plantillaMenu .= '<a href="#" class="nav-link '.$verificaActivo.'" ><i class="'.$expDatos[1].' nav-icon"></i> <p>'.$expDatos[0].'<i class="fa fa-angle-left right"></i></p></a>';
                    $plantillaMenu .= '<ul class="nav nav-treeview '.$verificaActivo.'">';
                    foreach($v as $k_1 => $v_1){
                        $expSubDatos = explode('|', $k_1);
                        $verificaSubActivo = (count($expSubDatos) >= 3) ? 'active' : '';
                        if(is_array($v_1)){
                            $plantillaMenu .= '<li class="nav-item">';
                            $plantillaMenu .= '<a href="#" class="nav-link '.$verificaSubActivo.'"><i class="'.$expSubDatos[1].' nav-icon"></i> <p>'.$expSubDatos[0].'<i class="fa fa-angle-left right"></p></i></a>';
                            $plantillaMenu .= '<p>'.$this->generarMenu($v_1).'</p>';
                            $plantillaMenu .= '</li>';
                        } else{
                            if(is_int($k_1)){
                                $plantillaMenu .= '<li class="nav-header">'.$v_1.'</li>';
                            } else{
                                $plantillaMenu .= '<li class="nav-item ">';
                                $plantillaMenu .= '<a href="'.$v_1.'" class="nav-link '.$verificaSubActivo.'"><i class="'.$expSubDatos[1].' nav-icon"></i> <p>'.$expSubDatos[0].'</p></a>';
                                $plantillaMenu .= '</li>';
                            }
                        }
                    }
                    $plantillaMenu .= '</ul>';
                    $plantillaMenu .= '</li>';
                } else{
                    if(is_int($k)){
                        $plantillaMenu .= '<li class="nav-header">'.$v.'</li>';
                    } else{
                        $plantillaMenu .= '<li class="nav-item">';
                        $plantillaMenu .= '<a href="'.$v.'" class="nav-link '.$verificaActivo.'"><i class="'.$expDatos[1].' nav-icon"></i><p>'.$expDatos[0].'</p></a>';
                        $plantillaMenu .= '</li>';
                    }
                }
            }
            $plantillaMenu .= '</ul>';
        }
        return $plantillaMenu;
    }
    
}