<?php

if (!function_exists('mensaje_error')) {
     function mensaje_error($mensaje, $opcion = FALSE) {
        $texto= '<div class="alert alert-danger alert-dismissable">';
        $texto .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        if($opcion === TRUE){
            $texto .= '<strong><i class="glyphicon glyphicon-bell"></i> !!ALERTA DE ERROR¡¡ </strong>'
                    . '<ul style="padding-left:32px;font-style:italic;font-weight:bold;">'.$mensaje.'</ul>';
        } else{
            $texto .= '<strong><i class="glyphicon glyphicon-bell"></i> !!ALERTA DE ERROR¡¡ </strong>'
                    . '<ul style="padding-left:32px;font-style:italic;font-weight:bold;">'
                    . '<li style="padding: 5px;">'.$mensaje.'</li></ul>';
        }
        $texto .= '</div>';
        return $texto;
    }
}

if (!function_exists('mensaje_exito')) {
     function mensaje_exito($mensaje, $opcion = FALSE) {
        $texto = '<div class="alert alert-success alert-dismissable">';
        $texto .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        if($opcion === TRUE){
            $texto .= '<strong><i class="glyphicon glyphicon-bell"></i> !!ALERTA DE EXITO¡¡ </strong>'
                    . '<ul style="padding-left:32px;font-style:italic;font-weight:bold;">'.$mensaje.'</ul>';
        } else{
            $texto .= '<strong><i class="glyphicon glyphicon-bell"></i> !!ALERTA DE EXITO¡¡ </strong>'
                    . '<ul style="padding-left:32px;font-style:italic;font-weight:bold;">'
                    . '<li style="padding: 5px;">'.$mensaje.'</li></ul>';
        }
        $texto .= '</div>';
        return $texto;
    }
}

if (!function_exists('mensaje_alerta')) {
     function mensaje_alerta($mensaje){
        $texto = '<div class=""><div class="row"><div class="col-md-12"><div class="box box-success box-solid bg-green-gradient">';
        $texto .= '';
        $texto .= '<div class="box-body">';
        $texto .= $mensaje;
        $texto .= '</div></div></div></div></div>';
        return $texto;
    }
}


if (!function_exists('alerta_error')) { 
     function alerta_error($mensaje,$tiempo = '',$vinculoUrl = '') {
        $texto = '';
        $texto .= '<script>';
        $texto .= 'if(!alertify.errorAlert){';
        $texto .= 'alertify.dialog("errorAlert",function factory(){';
        $texto .= 'return{';
        $texto .= 'build:function(){';
        $texto .= "var errorHeader = ".'"'."<span class='fa fa-times-circle fa-lg text-red' style='vertical-align:middle;font-size: 110%;color:red;'></span> ¡¡ERROR!!".'"'.";";
        $texto .= 'this.setHeader(errorHeader);';
        $texto .= '}};},true, "alert");}';
        $texto .= 'alertify.errorAlert(\''.$mensaje.'\');';
        $texto .= '</script>';
        if(!empty($tiempo) && empty($vinculoUrl)){
            $texto .= '<script>';
                if($tiempo != ''){
                    $obtienePeriodo = $tiempo * 1000;
                    $texto .= 'setTimeout( function(){ parent.location.reload(); }, '.$obtienePeriodo.' );';
                } else{
                    $texto .= 'parent.location.reload()';
                }
            $texto .= '</script>';
        }
        if(!empty($vinculoUrl) && !empty($vinculoUrl)){
            $texto .= '<script>';
                if($tiempo != ''){
                    $obtienePeriodo = $tiempo * 1000;
                    $texto .= 'setTimeout( function(){ location.href="'.$vinculoUrl.'"; }, '.$obtienePeriodo.' );';
                } else{
                    $texto .= 'location.href="'.$vinculoUrl.'"';
                }
            $texto .= '</script>';
        }
        return $texto;
    }
}

if (!function_exists('alerta_importante')) {  
     function alerta_importante($mensaje,$tiempo = '') {
        $texto = '<script>';
        $texto .= 'if(!alertify.errorAlert){';
        $texto .= 'alertify.dialog("errorAlert",function factory(){';
        $texto .= 'return{';
        $texto .= 'build:function(){';
        $texto .= "var errorHeader = ".'"'."<span class='fa fa-warning fa-lg text-yellow' style='vertical-align:middle;font-size: 110%;color:#fbc429;'></span> ¡¡AVISO!!".'"'.";";
        $texto .= 'this.setHeader(errorHeader);';
        $texto .= '}};},true, "alert");}';
        $texto .= 'alertify.errorAlert(\'<h4>'.$mensaje.'</h4>\');';
        $texto .= '</script>';
        if(!empty($tiempo)){
            if($tiempo != ''){
                $obtienePeriodo = $tiempo * 1000;
                $texto .= 'setTimeout( function(){ parent.location.reload(); }, '.$obtienePeriodo.' );';
            } else{
                $texto .= 'parent.location.reload()';
            }
        }
        $texto .= '</script>';
        return $texto;
    }
}

if (!function_exists('alerta_exito')) {  
     function alerta_exito($mensaje,$tiempo = '',$vinculoUrl = '') {
        $texto = '';
        $texto .= '<script>';
        $texto .= 'if(!alertify.successAlert){';
        $texto .= 'alertify.dialog("successAlert",function factory(){';
        $texto .= 'return{';
        $texto .= 'build:function(){';
        $texto .= "var errorHeader = ".'"'."<span class='fa fa-check-circle fa-lg text-green' style='vertical-align:middle;font-size: 110%;color:#6bd827;'></span> ¡¡EXITO!!".'"'.";";
        $texto .= 'this.setHeader(errorHeader);';
        $texto .= '}};},true, "alert");}';
        $texto .= 'alertify.successAlert(\''.$mensaje.'\');';
        $texto .= '</script>';
        if(!empty($tiempo) && empty($vinculoUrl)){
            $texto .= '<script>';
                if($tiempo != ''){
                    $obtienePeriodo = $tiempo * 1000;
                    $texto .= 'setTimeout( function(){ parent.location.reload(); }, '.$obtienePeriodo.' );';
                } else{
                    $texto .= 'parent.location.reload()';
                }
            $texto .= '</script>';
        }
        if(!empty($vinculoUrl) && !empty($vinculoUrl)){
            $texto .= '<script>';
                if($tiempo != ''){
                    $obtienePeriodo = $tiempo * 1000;
                    $texto .= 'setTimeout( function(){ location.href="'.$vinculoUrl.'"; }, '.$obtienePeriodo.' );';
                } else{
                    $texto .= 'location.href="'.$vinculoUrl.'"';
                }
            $texto .= '</script>';
        }
        return $texto;
    }
}

if (!function_exists('mensaje')) { 
     function mensaje($mensaje){
        $texto = '<div class="container row"><div class="row col-md-12"><div class="row col-md-6"><div class="box box-success box-solid">';
        $texto .= '';
        $texto .= '<div class="box-body">';
        $texto .= $mensaje;
        $texto .= '</div></div></div></div></div>';
        return $texto;
    }
}

if (!function_exists('re_direccion')) { 
     function re_direccion($vinculoUrl, $tiempo = ''){
        $texto = '<script>';
        if($tiempo != ''){
            $obtienePeriodo = $tiempo * 1000;
            $texto .= 'setTimeout( function(){ location.href="'.$vinculoUrl.'"; }, '.$obtienePeriodo.' );';
        } else{
            $texto .= 'location.href="'.$vinculoUrl.'"';
        }
        $texto .= '</script>';
        return $texto;
    }
}
    
if (!function_exists('refrescar')) {        
     function refrescar($tiempo = ''){
        $texto = '<script>';
        if($tiempo != ''){
            $obtienePeriodo = $tiempo * 1000;
            $texto .= 'setTimeout( function(){ parent.location.reload(); }, '.$obtienePeriodo.' );';
        } else{
            $texto .= 'parent.location.reload()';
        }
        $texto .= '</script>';
        return $texto;
    }
}
    
if (!function_exists('tiempo_periodo')) {
     function tiempo_periodo($tiempo){
        $obtienePeriodo = $tiempo * 1000;
        $texto = '<script>';
        $texto .= 'setTimeout( function(){ }, '.$obtienePeriodo.' );';
        $texto .= '</script>';
        return $texto; 
    }
}
    
if (!function_exists('alertas')) {
     function alertas($mensaje){
        $texto = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $texto .= '<script>';
        $texto .= 'alert("'.$mensaje.'")';
        $texto .= '</script>';
        return $texto;
    }
}

if (!function_exists('confirmar')) {
     function confirmar($mensaje, $opcion = array('yes' => '', 'no' => ''), $procesar = FALSE){
        $texto = '<meta charset="utf-8" />';
        $texto .= '<script>';
        $texto .= 'var choice = confirm("'.$mensaje.'"); ';
        $texto .= 'if(choice){ ';
        if($procesar === TRUE){
            $texto .= 'location.href="'.$opcion['yes'].'"; '; 
        }
        $texto .= '} else{ ';
        $texto .= 'location.href="'.$opcion['no'].'"; ';
        $texto .= '} ';
        $texto .= '</script>';
        return $texto;
    }
}
