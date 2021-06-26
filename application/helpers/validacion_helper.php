<?php

use Respect\Validation\Validator as v;

if (!function_exists('valida_campo')) {
    function valida_campo($post, $parametros, $etiqueta){
        $post = trim($post);
        $delimita = explode('|', trim(trim($parametros, '|')));
        $mensaje = '';
        foreach($delimita as $items){
            $valor = trim($items);
            $transValor = '';
            if(strpos($valor, 'starts_with') !== FALSE){
                $temp = str_replace('starts_with', '', $valor);
                $temp = str_replace(':', '', $temp);
                $transValor = $temp;
            }
            if(strpos($valor, 'ends_with') !== FALSE){
                $temp = str_replace('ends_with', '', $valor);
                $temp = str_replace(':', '', $temp);
                $transValor = $temp;
            }
            if(strpos($valor, 'contains') !== FALSE){
                $temp = str_replace('contains', '', $valor);
                $temp = str_replace(':', '', $temp);
                $transValor = $temp;
            }
            if(strpos($valor, 'equals') !== FALSE){
                $temp = str_replace('equals', '', $valor);
                $temp = str_replace(':', '', $temp);
                $transValor = $temp;
            }
            if(strpos($valor, 'password') !== FALSE){
                $temp = str_replace('password', '', $valor);
                $temp = str_replace('::', '', $temp);
                $transValor = $temp;
            }
            if(strpos($valor, 'identical') !== FALSE){
                $temp = str_replace('identical', '', $valor);
                $temp = str_replace(':', '', $temp);
                $transValor = $temp;
            }
            if(strpos($valor, 'creditcard') !== FALSE){
                $temp = str_replace('creditcard', '', $valor);
                $temp = str_replace(':', '', $temp);
                $transValor = $temp;
            }
            if(strpos($valor, 'minlength') !== FALSE){
                $temp = str_replace('minlength', '', $valor);
                $temp = str_replace(':', '', $temp);
                $transValor = $temp;
            }
            if(strpos($valor, 'maxlength') !== FALSE){
                $temp = str_replace('maxlength', '', $valor);
                $temp = str_replace(':', '', $temp);
                $transValor = $temp;
            }
            if(strpos($valor, 'minnumber') !== FALSE){
                $temp = str_replace('minnumber', '', $valor);
                $temp = str_replace(':', '', $temp);
                $transValor = $temp;
            }
            if(strpos($valor, 'maxnumber') !== FALSE){
                $temp = str_replace('maxnumber', '', $valor);
                $temp = str_replace(':', '', $temp);
                $transValor = $temp;
            }
            if(strpos($valor, 'languagecode') !== FALSE){
                $temp = str_replace('languagecode', '', $valor);
                $temp = str_replace(':', '', $temp);
                $transValor = (!empty($temp)) ? $temp : 'alpha-2';
            }
            if(strpos($valor, 'video') !== FALSE){
                $temp = str_replace('video', '', $valor);
                $temp = str_replace(':', '', $temp);
                $transValor = (!empty($temp)) ? $temp : 'youtube';
            }
            if(strpos($valor, 'charset') !== FALSE){
                $temp = str_replace('charset', '', $valor);
                $temp = str_replace(':', '', $temp);
                $transValor = (!empty($temp)) ? $temp : 'ASCII';
            }
            $transEtiqueta = $etiqueta;
            if( ($valor == 'required') && (v::notBlank()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Es necesario llenar información. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'numeric') && ($post != '') && (v::numeric()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente carácteres numéricos. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'text') && ($post != '') && (v::alpha()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente carácteres alfabéticos. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'alnum') && ($post != '') && (v::alnum()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente carácteres alfanuméricos: [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'alnum_upper') && ($post != '') && (v::alnum()->uppercase()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente carácteres alfanuméricos y mayúsculas. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'alnum_lower') && ($post != '')&& (v::alnum()->lowercase()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente carácteres alfanuméricos y minúsculas. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'alnum_notspace') && ($post != '') && (v::alnum()->noWhitespace()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente carácteres alfanuméricos sin espacios en blanco. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'php_label') && ($post != '') && (v::phpLabel()->validate($post) !== TRUE) ){
                $mensaje .= '<li>No se puede utilizar como nombre de variable. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( (strpos($valor, 'minlength') !== FALSE) && ($post != '') && (v::stringType()->length($transValor, null)->validate($post) !== TRUE) ){
                $mensaje .= '<li>El texto ingresado es menor a '.$transValor.' carácteres. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( (strpos($valor, 'maxlength') !== FALSE) && ($post != '') && (v::stringType()->length(null, $transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>El texto ingresado es mayor a '.$transValor.' carácteres. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( (strpos($valor, 'minnumber') !== FALSE) && ($post != '') && (v::intVal()->min($transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>El número ingresado es menor de '.$transValor.'. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( (strpos($valor, 'maxnumber') !== FALSE) && ($post != '') && (v::intVal()->max($transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>El número ingresado es mayor de '.$transValor.'. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( (strpos($valor, 'starts_with') !== FALSE) && ($post != '') && (v::startsWith($transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>No coincide la etiqueta inicial <b>'.$transValor.'</b>. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( (strpos($valor, 'ends_with') !== FALSE) && ($post != '') && (v::endsWith($transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>No coincide la etiqueta final <b>'.$transValor.'</b>. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( (strpos($valor, 'contains') !== FALSE) && ($post != '') && (v::contains($transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>No se encontro la etiqueta <b>'.$transValor.'</b>. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( (strpos($valor, 'equals') !== FALSE) && ($post != '') && (v::equals($transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>No coinciden las etiquetas: <b><small>'.$post.'</small></b> y <b><small>'.$transValor.'</small></b></li>';
            }else
             if( (strpos($valor, 'password') !== FALSE) && ($post != '') && (v::equals($transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>No coinciden los datos ingresados. [CAMPO] <b>'.$transEtiqueta.'</b></li>';
            }else
             if( (strpos($valor, 'identical') !== FALSE) && ($post != '') && (v::identical($transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>Los datos ingresados no son identicos: <b><small>'.$post.'</small></b> y <b><small>'.$transValor.'</small></b></li>';
            }else
             if( (strpos($valor, 'creditcard') !== FALSE) && ($post != '') && (v::creditCard($transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente tarjetas de crédito. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( (strpos($valor, 'languagecode') !== FALSE) && ($post != '') && (v::languageCode($transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente codigo de lenguaje. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( (strpos($valor, 'video') !== FALSE) && ($post != '') && (v::videoUrl($transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente urls de video. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( (strpos($valor, 'age') !== FALSE) && ($post != '') && (v::age($transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se fecha es menor a <b>'. $transValor .'</b>. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( (strpos($valor, 'charset') !== FALSE) && ($post != '') && (v::charset($transValor)->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten caracteres con formato <b>'. $transValor .'</b>. [CAMPO]'.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'roman') && ($post != '') && (v::roman()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente números romanos. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'vowel') && ($post != '') && (v::vowel()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente vocales. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'consonant') && ($post != '') && (v::consonant()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente consonantes. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'string') && ($post != '') && (v::stringType()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente cadenas de texto. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'slug') && ($post != '') && (v::slug()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente texto amigable. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'upper') && ($post != '') && (v::stringType()->uppercase()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente mayúsculas. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'lower') && ($post != '') && (v::stringType()->lowercase()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente minúsculas. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'not_empty') && (v::stringType()->notEmpty()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Es necesario llenar información. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'not_empty_array') && (v::arrayVal()->notEmpty()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Es necesario llenar información. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'not_space') && ($post != '') && (v::noWhitespace()->validate($post) !== TRUE) ){
                $mensaje .= '<li>No se permiten espacios en blanco. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'boolean_val') && ($post != '') && (v::boolVal()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente valores <b>booleanos</b>. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'boolean_type') && ($post != '') && (v::boolType()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente valores <b>booleanos</b>. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'float') && ($post != '') && (v::floatType()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente valores <b>flotantes</b>. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'not') && ($post != '') && (v::no()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente valor <b>no</b>. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'yes') && ($post != '') && (v::yes()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente valor <b>si</b>. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'countrycode') && ($post != '') && (v::countryCode()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente codigo de páis. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'currencycode') && ($post != '') && (v::currencyCode()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente codigo de monedas. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'mac') && ($post != '') && (v::macAddress()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente dirección mac. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'ip') && ($post != '') && (v::ip()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente dirección ip. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'phone') && ($post != '') && (v::phone()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente numeros telefónicos. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'url') && ($post != '') && (v::url()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente direcciones con formato URL. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'json') && ($post != '') && (v::json()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente formato json. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'domain') && ($post != '') && (v::domain()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente dominios. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'email') && ($post != '') && (v::email()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente direcciones de correo electrónico. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'imei') && ($post != '') && (v::imei()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente imei. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'negative') && ($post != '') && (v::numeric()->negative()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente números negativos. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'positive') && ($post != '') && (v::numeric()->positive()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente números positivos. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'rgb') && ($post != '') && (v::hexRgbColor()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente colores formato rgb. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'xdigit') && ($post != '') && (v::xdigit()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente hexadecimales. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'int') && ($post != '') && (v::intType()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente valores integer. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'version') && ($post != '') && (v::version()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente valores de version. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'leapyear') && ($post != '') && (v::leapYear()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente años bisiestos. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'date') && ($post != '') && (v::date()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente fechas. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'finite') && ($post != '') && (v::finite()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente números finitos. [CAMPO] '.$transEtiqueta.'</li>';
            }else
             if( ($valor == 'infinite') && ($post != '') && (v::infinite()->validate($post) !== TRUE) ){
                $mensaje .= '<li>Se permiten únicamente números infinitos. [CAMPO] '.$transEtiqueta.'</li>';
            }
        }
        if($mensaje != ''){
            return $mensaje;
        } else{
            return '';
        }
    }
}

if(!function_exists('valida_imagen')){
    function valida_imagen($post, $parametros, $etiqueta, $multiple = FALSE){
        $ci =& get_instance();
        $librerias = array('image','complementos');
        $ci->load->library($librerias);

        $transEtiqueta = '<small>'.$etiqueta.'</small>';
        $mensaje = '';
        if($post !== FALSE){
            $delimita = explode('|', trim(trim($parametros, '|')));
            if($multiple){
                for($i=0; $i<count($post); $i++){
                    foreach($delimita as $items){
                        $valor = trim($items);
                        $transValor = '';
                        $ci->image->ready($post[$i]['tmp_name'], TRUE);
                        if( strpos($valor, 'ratio') !== FALSE){
                            $temp = str_replace('ratio', '', $valor);
                            $temp = str_replace(':', '', $temp);
                            $data = explode('*', $temp);
                            $ratioAncho = (int) $data[0];
                            $ratioAlto = (int) $data[1];
                            $ratio = $ci->ratio($ratioAncho, $ratioAlto, $ci->image->getWidth());
                        }
                        if( strpos($valor, 'maxwidth') !== FALSE){
                            $temp = str_replace('maxwidth', '', $valor);
                            $temp = str_replace(':', '', $temp);
                            $transValor = (int) $temp;
                        }
                        if( strpos($valor, 'maxheight') !== FALSE){
                            $temp = str_replace('maxheight', '', $valor);
                            $temp = str_replace(':', '', $temp);
                            $transValor = (int) $temp;
                        }
                        if( strpos($valor, 'minwidth') !== FALSE){
                            $temp = str_replace('minwidth', '', $valor);
                            $temp = str_replace(':', '', $temp);
                            $transValor = (int) $temp;
                        }
                        if( strpos($valor, 'minheight') !== FALSE){
                            $temp = str_replace('minheight', '', $valor);
                            $temp = str_replace(':', '', $temp);
                            $transValor = (int) $temp;
                        }
                        if( strpos($valor, 'maxsize') !== FALSE){
                            $temp = str_replace('maxsize', '', $valor);
                            $temp = str_replace(':', '', $temp);
                            $transValor = (int) $temp;
                        }
                        if( strpos($valor, 'minsize') !== FALSE){
                            $temp = str_replace('minsize', '', $valor);
                            $temp = str_replace(':', '', $temp);
                            $transValor = (int) $temp;
                        }

                        $number = round($post[$i]['size'] / 1048576, 2);
                        $getSize = (float) number_format($number, 2);

                        if( (strpos($valor, 'maxwidth') !== FALSE) && $ci->image->getWidth() > $transValor){
                            $mensaje .= '<li>La imágen "'.$post[$i]['name'].'" tiene un ancho superior a '.$transValor.' px. [CAMPO] '.$transEtiqueta.'</li>';
                        } else
                        if( (strpos($valor, 'maxheight') !== FALSE) && $ci->image->getHeight() > $transValor){
                            $mensaje .= '<li>La imágen "'.$post[$i]['name'].'" tiene un alto superior a '.$transValor.' px. [CAMPO] '.$transEtiqueta.'</li>';
                        } else
                        if( (strpos($valor, 'maxsize') !== FALSE) && $getSize > $transValor){
                            $mensaje .= '<li>La imágen "'.$post[$i]['name'].'" tiene un tamaño superior a '.$transValor.' MB. [CAMPO] '.$transEtiqueta.'</li>';
                        } else
                        if( (strpos($valor, 'minwidth') !== FALSE) && $ci->image->getWidth() < $transValor){
                            $mensaje .= '<li>La imágen "'.$post[$i]['name'].'" tiene un ancho inferior a '.$transValor.' px. [CAMPO] '.$transEtiqueta.'</li>';
                        } else
                        if( (strpos($valor, 'minheight') !== FALSE) && $ci->image->getHeight() < $transValor){
                            $mensaje .= '<li>La imágen "'.$post[$i]['name'].'" tiene un alto inferior a '.$transValor.' px. [CAMPO] '.$transEtiqueta.'</li>';
                        } else
                        if( (strpos($valor, 'minsize') !== FALSE) && $getSize < $transValor){
                            $mensaje .= '<li>La imágen "'.$post[$i]['name'].'" tiene un tamaño inferior a '.$transValor.' MB. [CAMPO] '.$transEtiqueta.'</li>';
                        } else
                        if( (strpos($valor, 'ratio') !== FALSE) && $ratio['alto'] != $ci->image->getHeight()){
                            $mensaje .= '<li>La imágen "'.$post[$i]['name'].'" debe de tener una dimensión que encaje a '.$ratioAncho.'*'.$ratioAlto.' px. [CAMPO] '.$transEtiqueta.'</li>';
                        }
                    }
                }
            } else
            if(!$multiple){
                foreach($delimita as $items){
                    $valor = trim($items);
                    $transValor = '';
                    $ci->image->ready($post['tmp_name'], TRUE);
                    if( strpos($valor, 'ratio') !== FALSE){
                        $temp = str_replace('ratio', '', $valor);
                        $temp = str_replace(':', '', $temp);
                        $data = explode('*', $temp);
                        $ratioAncho = (int) $data[0];
                        $ratioAlto = (int) $data[1];
                        $ratio = $ratio($ratioAncho, $ratioAlto, $ci->image->getWidth());
                    }
                    if( strpos($valor, 'maxwidth') !== FALSE){
                        $temp = str_replace('maxwidth', '', $valor);
                        $temp = str_replace(':', '', $temp);
                        $transValor = (int) $temp;
                    }
                    if( strpos($valor, 'maxheight') !== FALSE){
                        $temp = str_replace('maxheight', '', $valor);
                        $temp = str_replace(':', '', $temp);
                        $transValor = (int) $temp;
                    }
                    if( strpos($valor, 'minwidth') !== FALSE){
                        $temp = str_replace('minwidth', '', $valor);
                        $temp = str_replace(':', '', $temp);
                        $transValor = (int) $temp;
                    }
                    if( strpos($valor, 'minheight') !== FALSE){
                        $temp = str_replace('minheight', '', $valor);
                        $temp = str_replace(':', '', $temp);
                        $transValor = (int) $temp;
                    }
                    if( strpos($valor, 'maxsize') !== FALSE){
                        $temp = str_replace('maxsize', '', $valor);
                        $temp = str_replace(':', '', $temp);
                        $transValor = (int) $temp;
                    }
                    if( strpos($valor, 'minsize') !== FALSE){
                        $temp = str_replace('minsize', '', $valor);
                        $temp = str_replace(':', '', $temp);
                        $transValor = (int) $temp;
                    }

                    $number = round($post['size'] / 1048576, 2);
                    $getSize = (float) number_format($number, 2);

                    if( (strpos($valor, 'maxwidth') !== FALSE) && $ci->image->getWidth() > $transValor){
                        $mensaje .= '<li>La imágen "'.$post['name'].'" tiene un ancho superior a '.$transValor.' px. [CAMPO] '.$transEtiqueta.'</li>';
                    } else
                    if( (strpos($valor, 'maxheight') !== FALSE) && $ci->image->getHeight() > $transValor){
                        $mensaje .= '<li>La imágen "'.$post['name'].'" tiene un alto superior a '.$transValor.' px. [CAMPO] '.$transEtiqueta.'</li>';
                    } else
                    if( (strpos($valor, 'maxsize') !== FALSE) && $getSize > $transValor){
                        $mensaje .= '<li>La imágen "'.$post['name'].'" tiene un tamaño superior a '.$transValor.' MB. [CAMPO] '.$transEtiqueta.'</li>';
                    } else
                    if( (strpos($valor, 'minwidth') !== FALSE) && $ci->image->getWidth() < $transValor){
                        $mensaje .= '<li>La imágen "'.$post['name'].'" tiene un ancho inferior a '.$transValor.' px. [CAMPO] '.$transEtiqueta.'</li>';
                    } else
                    if( (strpos($valor, 'minheight') !== FALSE) && $ci->image->getHeight() < $transValor){
                        $mensaje .= '<li>La imágen "'.$post['name'].'" tiene un alto inferior a '.$transValor.' px. [CAMPO] '.$transEtiqueta.'</li>';
                    } else
                    if( (strpos($valor, 'minsize') !== FALSE) && $getSize < $transValor){
                        $mensaje .= '<li>La imágen "'.$post['name'].'" tiene un tamaño inferior a '.$transValor.' MB. [CAMPO] '.$transEtiqueta.'</li>';
                    } else
                    if( (strpos($valor, 'ratio') !== FALSE) && $ratio['alto'] != $ci->image->getHeight()){
                        $mensaje .= '<li>La imágen "'.$post['name'].'" debe de tener una dimensión que encaje a '.$ratioAncho.'*'.$ratioAlto.' px. [CAMPO] '.$transEtiqueta.'</li>';
                    }
                }
            }
        } else{
            $mensaje .= '<li>No existe ninguna imágen(es) ingresada(s). [CAMPO] '.$transEtiqueta.'</li>';
        }
        if($mensaje != ''){
            return $mensaje;
        } else{
            return '';
        }
    }
}
