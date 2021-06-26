<?php

class Complementos {

    private $items = array();

    public function __construct() {
        $this->ci =& get_instance();
        $librerias = array();
        $helper = array();
        $modelos = array();
        $this->ci->load->library($librerias);
        $this->ci->load->helper($helper);
        $this->ci->load->model($modelos);
    }

    public function obtenerFecha($tiempo, $tipoEstilo = ''){
        date_default_timezone_set('America/Lima');
        if(($tipoEstilo == 'date') || ($tipoEstilo == 'datetime') || ($tipoEstilo == '')){
            $generaFecha = date('Y-m-d H:i:s a', $tiempo);
        } else{
            $generaFecha = date('Y-m-d h:i:s a', $tiempo);
        }
        $delimitador = explode(" ", $generaFecha);
        $obtieneFecha = explode("-", $delimitador[0]);
        $dia = $obtieneFecha[2];
        $mes = $obtieneFecha[1];
        $ano = $obtieneFecha[0];
        $obtieneHora = explode(":", $delimitador[1]);
        $segundo = $obtieneHora[2];
        $minuto = $obtieneHora[1];
        $hora = $obtieneHora[0];
        $meridiano = strtolower($delimitador[2]);
        if($tipoEstilo == ''){
            return $generaFecha;
        } else{
            switch($tipoEstilo){
                case 1:
                    $resultado = $dia.' de '.$this->parseoMes($mes);
                    break;
                case 2:
                    $resultado = $dia.' de '.$this->parseoMes($mes).' del '.$ano;
                    break;
                case 3:
                    $resultado = $this->parseoMes($mes).' '.$dia.', '.$ano;
                    break;
                case 4:
                    $resultado = $dia.' '.$this->parseoMes($mes).' '.$hora.':'.$minuto.' '.$meridiano;
                    break;
                case 5:
                    $resultado = $dia.' de '.$this->parseoMes($mes).' a las '.$hora.':'.$minuto.' '.$meridiano;
                    break;
                case 6:
                    $resultado = $dia.' '.$this->parseoMes($mes, TRUE).' '.$ano;
                    break;
                case 7:
                    $resultado = $dia.' de '.$this->parseoMes($mes).' del '.$ano.' a las '.$hora.':'.$minuto.' '.$meridiano;
                    break;
                case 8:
                    $resultado = $this->parseoMes($mes, TRUE).' '.$dia.', '.$ano;
                    break;
                case 'date':
                    $resultado = $dia.'-'.$mes.'-'.$ano;
                    break;
                case 'datetime':
                    $resultado = $dia.'-'.$mes.'-'.$ano.' '.$hora.':'.$minuto.':'.$segundo;
                    break;
                default:
                    return $generaFecha;
            }
            return $resultado;
        }
    }

    public function cantidadDiasPorMes($mes, $ano = '') {
        switch ($mes) {
            case 1:case 01: $dias = 31; break;
            case 2:case 02:
                if($ano != ''){
                    $obtieneAno = $ano;
                } else{
                    $obtieneAno = date('Y');
                }
                $dias = (($obtieneAno%4==0 && $obtieneAno%100!=0) || $obtieneAno%400==0) ? 29 : 28;
                break;
            case 3:case '03': $dias = 31; break;
            case 4:case '04': $dias = 30; break;
            case 5:case '05': $dias = 31; break;
            case 6:case '06': $dias = 30; break;
            case 7:case '07': $dias = 31; break;
            case 8:case '08': $dias = 31; break;
            case 9:case '09': $dias = 30; break;
            case 10: $dias = 31; break;
            case 11: $dias = 30; break;
            case 12: $dias = 31; break;
        }
        return $dias;
    }

    public function parseoDia($dia, $minificado = FALSE) {
        switch($dia) {
            case 1: case '01': return (!$minificado) ? "Lunes" : "Lun";
            case 2: case '02': return (!$minificado) ? "Martes" : "Mar";
            case 3: case '03': return (!$minificado) ? "Miércoles" : "Mie";
            case 4: case '04': return (!$minificado) ? "Jueves" : "Jue";
            case 5: case '05': return (!$minificado) ? "Viernes" : "Vie";
            case 6: case '06': return (!$minificado) ? "Sábado" : "Sab";
            case 7: case '07': return (!$minificado) ? "Domingo" : "Dom";
        }
    }

    public function parseoMes($mes, $minificado = FALSE) {
        switch ($mes) {
            case 1: case '01': return (!$minificado) ? "Enero" : "Ene";
            case 2: case '02': return (!$minificado) ? "Febrero" : "Feb";
            case 3: case '03': return (!$minificado) ? "Marzo" : "Mar";
            case 4: case '04': return (!$minificado) ? "Abril" : "Abr";
            case 5: case '05': return (!$minificado) ? "Mayo" : "May";
            case 6: case '06': return (!$minificado) ? "Junio" : "Jun";
            case 7: case '07': return (!$minificado) ? "Julio" : "Jul";
            case 8: case '08': return (!$minificado) ? "Agosto" : "Ago";
            case 9: case '09': return (!$minificado) ? "Septiembre" : "Sep";
            case 10: return (!$minificado) ? "Octubre" : "Oct";
            case 11: return (!$minificado) ? "Noviembre" : "Nov";
            case 12: return (!$minificado) ? "Diciembre" : "Dic";
        }
    }

    public function verificarExtensionArchivo($nombreArchivo, $extension = 'jpeg|jpg|png|gif') {
        $delimitarNombre = strtolower($nombreArchivo);
        $delimitarTipos = explode('|', $extension);
        $datosTmp = array();
        foreach($delimitarTipos as $items){
            $datosTmp[] = trim($items);
        }
        $listaBlanca = $datosTmp;
        $listaNegra = array('php', 'php3', 'php4', 'phtml', 'exe');
        $delimitarExtension = explode('.', $delimitarNombre);
        $obtieneExtension = strtolower(end($delimitarExtension));
        if (!in_array($obtieneExtension, $listaBlanca)) {
            return FALSE;
        } elseif (in_array($obtieneExtension, $listaNegra)) {
            return FALSE;
        }
        return TRUE;
    }

    public function aleatorio($longitud = 25, $minuscula = TRUE, $mayuscula = FALSE, $numero = TRUE, $caracterEspecial = FALSE) {
        $source = '';
        if ($minuscula === TRUE) {
            $source .= 'abcdefghijklmnopqrstuvwxyz';
        }
        if ($mayuscula === TRUE) {
            $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($numero === TRUE) {
            $source .= '1234567890';
        }
        if ($caracterEspecial === TRUE) {
            $source .= '|@#~$%()=^*+[]{}-_';
        }
        if ($longitud > 0) {
            $resultado = '';
            $source = str_split($source, 1);
            for ($i=1; $i<=$longitud; $i++) {
                mt_srand((double) microtime() * 1000000);
                $obtieneNumero = mt_rand(1, count($source));
                $resultado .= $source[$obtieneNumero - 1];
            }
        }
        return $resultado;
    }

    public function almacenarImagen($imagen, $ubicacionCarpeta, $opciones, $nuevoNombre = FALSE){
        $opDefecto = array(
            'marcaTipo' => FALSE, // 'texto', 'imagen', FALSE
            'marcaInfo' => FALSE, // ([texto] => 'palabra de texto'), ([imagen] => 'ruta de la imagen')
            'ajuste' => 'w', // 'w', 'h', 'auto', 'noauto'
            'ajusteImagen' => 1900, // ['w', 'h'] => 1366, ['auto', 'noauto'] => '1366*768'
            'desenfocado' => FALSE, // 0-100, FALSE
            'cantidadCopia' => array(), // [numero] => 'Arreglo de elementos con respecto a su cantidad'
            'base64' => FALSE // TRUE, FALSE
        );
        $opGenerada = array_merge($opDefecto, $opciones);
        if($opGenerada['base64']){
            $tempImagen = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagen));
            if($nuevoNombre !== FALSE){
                $delimitar = explode('.', 'temp.png');
                $extension = strtolower(end($delimitar));
                $transformaArchivo = $this->generaTextoAmigable($delimitar[0], '_');
                $obtieneArchivo = $opGenerada['cantidadCopia'];
                $generaNombre = '';
                if(!empty($obtieneArchivo)){
                    $generaNombre = $transformaArchivo.'_'.$this->aleatorio().'_copia'.count($obtieneArchivo).'_';
                } else{
                    $generaNombre = $transformaArchivo.'_'.$this->aleatorio().'_';
                }
                $resultado = $generaNombre.'.'.$extension;
            } else{
                $delimitar = explode('.', 'temp.png');
                $extension = strtolower(end($delimitar));
                $resultado = $this->aleatorio().'.'.$extension;
            }
            file_put_contents('./'.$ubicacionCarpeta.'/'.$resultado, $tempImagen);
            return $resultado;
        }
        $this->ci->image->ready($imagen['tmp_name'], TRUE);
        switch($opGenerada['ajuste']){
            case 'w':
                $ancho = (is_int($opGenerada['ajusteImagen'])) ? $opGenerada['ajusteImagen'] : 1920;
                $this->ci->image->resizeToWidth($ancho); break;
            case 'h':
                $alto = (is_int($opGenerada['ajusteImagen'])) ? $opGenerada['ajusteImagen'] : 1200;
                $this->ci->image->resizeToHeight($alto); break;
            case 'auto':
                $ajuste = (strpos($opGenerada['ajusteImagen'], '*') !== FALSE) ? $opGenerada['ajusteImagen'] : '1920*1200';
                $delimitador = explode('*', trim($ajuste));
                $this->ci->image->resizeToFit($delimitador[0], $delimitador[1], TRUE, 'FFFFFF'); break;
            case 'noauto':
                $ajuste = (strpos($opGenerada['ajusteImagen'], '*') !== FALSE) ? $opGenerada['ajusteImagen'] : '1920*1200';
                $delimitador = explode('*', trim($ajuste));
                $this->ci->image->resize($delimitador[0], $delimitador[1]); break;
            default:
                $ancho = (is_int($opGenerada['ajusteImagen'])) ? $opGenerada['ajusteImagen'] : 1920;
                $this->ci->image->resizeToWidth($ancho); break;
        }
        if($opGenerada['marcaTipo'] !== FALSE && $opGenerada['marcaInfo'] !== FALSE){
            switch($opGenerada['marcaTipo']){
                case 'texto':
                    $this->ci->image->stringWatermark($opGenerada['marcaInfo'], 70, 'FFFFFF', 'top right', 10, 10); break;
                case 'imagen':
                    $this->ci->image->imgWatermark($opGenerada['marcaInfo'], 70, 'top right', 10, 10); break;
            }
        }
        if($opGenerada['desenfocado'] !== FALSE){
            $desenfocado = (int) $opGenerada['desenfocado'];
            $desenfocado = ($desenfocado <= 0) ? 0 : $desenfocado;
            $this->ci->image->onFocused($desenfocado);
        }
        if($nuevoNombre !== FALSE){
            $delimitar = explode('.', $imagen['name']);
            $extension = strtolower(end($delimitar));
            $transformaArchivo = $this->generaTextoAmigable($delimitar[0], '_');
            $obtieneArchivo = $opGenerada['cantidadCopia'];
            $generaNombre = '';
            if(!empty($obtieneArchivo)){
                $generaNombre = $transformaArchivo.'_'.time().'_'.count($obtieneArchivo);
            } else{
                $generaNombre = $transformaArchivo.'_'.time();
            }
            $resultado = $generaNombre.'.'.$extension;
        } else{
            $delimitar = explode('.', $imagen['name']);
            $extension = strtolower(end($delimitar));
            $resultado = $this->aleatorio().'.'.$extension;
        }
        $this->ci->image->save('./'.$ubicacionCarpeta.'/'.$resultado, 100);
        return $resultado;
    }

    public function guardarMultipleImagenes($documento, $directorio, $tamaño = 1600,$opciones) {
        $i = 1;
        $this->ci = & get_instance();
        $this->ci->load->library('image');
        $opDefecto = array(
            'marcaTipo' => FALSE, // 'texto', 'imagen', FALSE
            'marcaInfo' => FALSE, // ([texto] => 'palabra de texto'), ([imagen] => 'ruta de la imagen')
            'ajuste' => 'w', // 'w', 'h', 'auto', 'noauto'
            'ajusteImagen' => 1900, // ['w', 'h'] => 1366, ['auto', 'noauto'] => '1366*768'
        );
        $opGenerada = array_merge($opDefecto, $opciones);
        for ($i = 0; $i < count($documento); $i++) {
            $this->ci->image->ready($documento[$i]['tmp_name'], TRUE);
            $this->ci->image->resizeToWidth($tamaño);
            switch($opGenerada['ajuste']){
                case 'w': 
                    $ancho = (is_int($opGenerada['ajusteImagen'])) ? $opGenerada['ajusteImagen'] : 1920;
                    $this->ci->image->resizeToWidth($ancho); break;
                case 'h':
                    $alto = (is_int($opGenerada['ajusteImagen'])) ? $opGenerada['ajusteImagen'] : 1200;
                    $this->ci->image->resizeToHeight($alto); break;
                case 'auto':
                    $ajuste = (strpos($opGenerada['ajusteImagen'], '*') !== FALSE) ? $opGenerada['ajusteImagen'] : '1920*1200';
                    $delimitador = explode('*', trim($ajuste));
                    $this->ci->image->resizeToFit($delimitador[0], $delimitador[1], TRUE, 'FFFFFF'); break;
                case 'noauto':
                    $ajuste = (strpos($opGenerada['ajusteImagen'], '*') !== FALSE) ? $opGenerada['ajusteImagen'] : '1920*1200';
                    $delimitador = explode('*', trim($ajuste));
                    $this->ci->image->resize($delimitador[0], $delimitador[1]); break;
                default:
                    $ancho = (is_int($opGenerada['ajusteImagen'])) ? $opGenerada['ajusteImagen'] : 1920;
                    $this->ci->image->resizeToWidth($ancho); break;
            }
            if($opGenerada['marcaTipo'] !== FALSE && $opGenerada['marcaInfo'] !== FALSE){
                switch($opGenerada['marcaTipo']){
                    case 'texto':
                        $this->ci->image->stringWatermark($opGenerada['marcaInfo'], 70, 'FFFFFF', 'top right', 10, 10); break;
                    case 'imagen':
                        $this->ci->image->imgWatermark($opGenerada['marcaInfo'], 50, 'middle center', 10, 10); break;
                }
            }
            $delimitar = explode('.', $documento[$i]['name']);
            $extension = strtolower(end($delimitar));
            $transformaArchivo = $this->generaTextoAmigable($delimitar[0], '_');
            $transformaProyecto = $this->generaTextoAmigable('m', '_');
            
            $generaNombre = $transformaProyecto.'_'.$transformaArchivo.'_'.$this->aleatorio(10, TRUE, TRUE, TRUE, FALSE);
            
            $resultado = $generaNombre.'.'.$extension;
            $this->ci->image->save('./' . $directorio . '/' . $resultado);
            $data[] = $resultado;
        }

        return $data;
    }

    public function almacenarArchivo($archivo, $ubicacionCarpeta, $opciones, $nuevoNombre = FALSE){
        $opDefecto = array(
            'nombreEmpresa' => 'SISTEMA', // [texto] => 'Nombre de la empresa'
            'cantidadCopia' => array() // [numero] => 'Arreglo de elementos con respecto a su cantidad'
        );
        $opGenerada = array_merge($opDefecto, $opciones);
        if($nuevoNombre !== FALSE){
            $delimitar = explode('.', $archivo['name']);
            $extension = strtolower(end($delimitar));
            $transformaArchivo = $this->generaTextoAmigable($delimitar[0], '_');
            $transformaProyecto = $this->generaTextoAmigable($opGenerada['nombreEmpresa'], '_');
            $obtieneArchivo = $opGenerada['cantidadCopia'];
            $generaNombre = '';
            if(!empty($obtieneArchivo)){
                $generaNombre = $transformaProyecto.'_'.$transformaArchivo.'_'.$this->aleatorio(10, FALSE, FALSE, TRUE, FALSE).'_copia'.count($obtieneArchivo);
            } else{
                $generaNombre = $transformaProyecto.'_'.$transformaArchivo.'_'.$this->aleatorio(10, FALSE, FALSE, TRUE, FALSE);
            }
            $resultado = $generaNombre.'.'.$extension;
        } else{
            $delimitar = explode('.', $archivo['name']);
            $extension = strtolower(end($delimitar));
            $resultado = $this->aleatorio().'.'.$extension;
        }
        move_uploaded_file($archivo['tmp_name'], $ubicacionCarpeta.'/'.$resultado);
        return $resultado;
    }

    public function guardarArchivo($archivo, $ubicacionCarpeta, $espacioMaximo = 5, $nuevoNombre = FALSE) {
        $mensajeAlerta = array();
        $archivoNombre = $archivo['name'];
        $archivoPeso = $archivo['size'];
        $tmpArchivoNombre = $archivo['tmp_name'];
        if(!$nuevoNombre){
            $generaNombreArchivo = $this->aleatorio();
        } else{
            $generaNombreArchivo = $nuevoNombre;
        }
        $this->ci->file->loadFile($archivoNombre, $ubicacionCarpeta, $archivoPeso, $tmpArchivoNombre, $generaNombreArchivo, $espacioMaximo);
        $resultado = $this->ci->file->uploadFile();
        if($resultado['archivo'] !== FALSE){
            $mensajeAlerta = array(
                'mensaje' => $resultado['mensaje'],
                'archivo' => $resultado['archivo']
            );
        } else{
            $mensajeAlerta = array(
                'mensaje' => $resultado['mensaje'],
                'archivo' => FALSE
            );
        }
        return $mensajeAlerta;
    }

    public function obtenerArchivo($archivo, $tipoSubida = 'unico', $extension = 'jpeg|jpg|png|gif'){
        switch($tipoSubida){
            case 'unico':
                if(isset($_FILES[$archivo]['tmp_name']) && $_FILES[$archivo]['tmp_name'] != '') {
                    $obtieneArchivo = $_FILES[$archivo];
                    $resultado = $this->verificarExtensionArchivo($obtieneArchivo['name'], $extension);
                    if(!$resultado){
                        return FALSE;
                    } else{
                        return $obtieneArchivo;
                    }
                } else{
                    return FALSE;
                }
                break;
            case 'muchos':
                if(isset($_FILES[$archivo]['tmp_name'][0]) && $_FILES[$archivo]['tmp_name'][0] != '') {
                    $obtieneArchivo = $_FILES[$archivo];
                    for($i=0; $i<count($obtieneArchivo['tmp_name']); $i++){
                        $resultado = $this->verificarExtensionArchivo($obtieneArchivo['name'][$i], $extension);
                        if(!$resultado){
                            continue;
                        } else{
                            $datosArchivo[] = array(
                                'name' => $obtieneArchivo['name'][$i],
                                'type' => $obtieneArchivo['type'][$i],
                                'tmp_name' => $obtieneArchivo['tmp_name'][$i],
                                'error' => $obtieneArchivo['error'][$i],
                                'size' => $obtieneArchivo['size'][$i]
                            );
                        }
                    }
                    if(isset($datosArchivo)){
                        return $datosArchivo;
                    }
                    return FALSE;
                } else{
                    return FALSE;
                }
                break;
            default:
                return FALSE;
        }
    }

    public function eliminarArchivo($nombreArchivo, $ubicacionCarpeta) {
        if (!file_exists('./'.$ubicacionCarpeta.'/'.$nombreArchivo)) {
            return FALSE;
        } else {
            @unlink('./'.$ubicacionCarpeta.'/'.$nombreArchivo);
            return TRUE;
        }
    }

    public function eliminarCarpeta($ubicacion) {
        foreach (glob($ubicacion."/*") as $archivos) {
            if (is_dir($archivos)) {
                $this->eliminarCarpeta($archivos);
            } else {
                unlink($archivos);
            }
        }
        return rmdir($ubicacion);
    }

    public function validaArchivo($post, $parametros, $etiqueta, $multiple = FALSE){
        $transEtiqueta = '<small>'.$etiqueta.'</small>';
        $mensaje = '';
        if($post !== FALSE){
            $delimita = explode('|', trim(trim($parametros, '|')));
            if($multiple){
                for($i=0; $i<count($post); $i++){
                    foreach($delimita as $items){
                        $valor = trim($items);
                        $transValor = '';
                        $this->ci->image->ready($post[$i]['tmp_name'], TRUE);
                        if( strpos($valor, 'maxsize') !== FALSE){
                            $temp = str_replace('maxsize', '', $valor);
                            $temp = str_replace('[', '', $temp);
                            $temp = str_replace(']', '', $temp);
                            $transValor = (int) $temp;
                        }
                        if( strpos($valor, 'minsize') !== FALSE){
                            $temp = str_replace('minsize', '', $valor);
                            $temp = str_replace('[', '', $temp);
                            $temp = str_replace(']', '', $temp);
                            $transValor = (int) $temp;
                        }

                        $number = round($post[$i]['size'] / 1048576, 2);
                        $getSize = (float) number_format($number, 2);

                        if( (strpos($valor, 'maxsize') !== FALSE) && $getSize > $transValor){
                            $mensaje .= '<li>El archivo "'.$post[$i]['name'].'" tiene un tamaño superior a '.$transValor.' MB. [CAMPO] '.$transEtiqueta.'</li>';
                        } else
                        if( (strpos($valor, 'minsize') !== FALSE) && $getSize < $transValor){
                            $mensaje .= '<li>El archivo "'.$post[$i]['name'].'" tiene un tamaño inferior a '.$transValor.' MB. [CAMPO] '.$transEtiqueta.'</li>';
                        }
                    }
                }
            } else
            if(!$multiple){
                foreach($delimita as $items){
                    $valor = trim($items);
                    $transValor = '';
                    $this->ci->image->ready($post['tmp_name'], TRUE);
                    if( strpos($valor, 'maxsize') !== FALSE){
                        $temp = str_replace('maxsize', '', $valor);
                        $temp = str_replace('[', '', $temp);
                        $temp = str_replace(']', '', $temp);
                        $transValor = (int) $temp;
                    }
                    if( strpos($valor, 'minsize') !== FALSE){
                        $temp = str_replace('minsize', '', $valor);
                        $temp = str_replace('[', '', $temp);
                        $temp = str_replace(']', '', $temp);
                        $transValor = (int) $temp;
                    }

                    $number = round($post['size'] / 1048576, 2);
                    $getSize = (float) number_format($number, 2);

                    if( (strpos($valor, 'maxsize') !== FALSE) && $getSize > $transValor){
                        $mensaje .= '<li>El archivo "'.$post['name'].'" tiene un tamaño superior a '.$transValor.' MB. [CAMPO] '.$transEtiqueta.'</li>';
                    } else
                    if( (strpos($valor, 'minsize') !== FALSE) && $getSize < $transValor){
                        $mensaje .= '<li>El archivo "'.$post['name'].'" tiene un tamaño inferior a '.$transValor.' MB. [CAMPO] '.$transEtiqueta.'</li>';
                    }
                }
            }
        } else{
            $mensaje .= '<li>No existe ningún archivo(s) ingresado(s). [CAMPO] '.$transEtiqueta.'</li>';
        }
        if($mensaje != ''){
            return $mensaje;
        } else{
            return '';
        }
    }


    public function ratio($ratioAncho, $ratioAlto, $ancho){
        $obtieneAncho = $ancho;
        $porcentAncho = (double) round((($ancho*100)/$ratioAncho), 2);
        $obtieneAlto = (int) round((($ratioAlto*$porcentAncho)/100));
        $data = array(
            'ancho' => $obtieneAncho,
            'alto' => $obtieneAlto
        );
        return $data;
    }

    public function ordenarPosicionArriba($datos, $element){
        sort($datos);
        $data = array();
        foreach($datos as $items){
            $data[] = (int) $items;
        }
        $position = array_keys($data, $element);
        if(!empty($position)){
            $tmp = array();
            $tmp_old = array();
            foreach($data as $k => $v){
                if($k < ($position[0] - 1)){
                    $tmp[] = (int) $v;
                } elseif($k == $position[0]){
                    $tmp[] = (int) $element;
                } else{
                    $tmp_old[] = $v;
                }
            }
            foreach($tmp_old as $k => $v){
                $tmp[] = (int) $v;
            }
            return $tmp;
        }
        return FALSE;
    }

    public function ordenarPosicionAbajo($datos, $element){
        sort($datos);
        $data = array();
        foreach($datos as $items){
            $data[] = (int) $items;
        }
        $position = array_keys($data, $element);
        if(!empty($position)){
            $tmp = array();
            $tmp_old = array();
            foreach($data as $k => $v){
                if($k < $position[0]){
                    $tmp[] = (int) $v;
                } elseif($k == ($position[0] + 1)){
                    $tmp[] = (int) $v;
                } else{
                    $tmp_old[] = $v;
                }
            }
            foreach($tmp_old as $k => $v){
                $tmp[] = (int) $v;
            }
            return $tmp;
        }
        return FALSE;
    }

    public function ordenarPosicion($datos, $posicionActual, $posicionNueva){
        if( (array_key_exists($posicionNueva, $datos)) && ($posicionActual != $posicionNueva) ){
            $tmp = array();
            $tmp_1 = array();
            $tmp_2 = array();
            $tmp_3 = array();
            $i = $posicionNueva;
            foreach($datos as $k => $v){
                if($k == $posicionNueva || $k == $posicionActual){
                    continue;
                } else{
                    if($posicionActual > $posicionNueva){
                        if($posicionNueva < $k){
                            $tmp_1[$i] = $v;
                        } else{
                            $tmp[$k] = $v;
                        }
                    }
                    if($posicionActual < $posicionNueva){
                        if($posicionNueva < $k){
                            $tmp_1[$i] = $v;
                        } else{
                            $tmp[$k] = $v;
                        }
                    }
                }
                $i++;
            }
            if($posicionActual > $posicionNueva){
                $tmp[$posicionNueva] = $datos[$posicionActual];
                $tmp[$posicionNueva+1] = $datos[$posicionNueva];
            }
            if($posicionActual < $posicionNueva){
                $tmp[$posicionNueva] = $datos[$posicionNueva];
                $tmp[$posicionNueva+1] = $datos[$posicionActual];
            }
            $tmp_2 = array_merge($tmp, $tmp_1);
            foreach($tmp_2 as $k => $v){
                $tmp_3[$k+1] = $v;
            }
            return $tmp_3;
        } else{
            return $datos;
        }
    }

    public function generaDesplegable($datos, $nombreImput, $valorDefecto = '', $texto = 'Seleccione una opción', $tipoSeleccion = 'none', $atributos = FALSE, $skin = 'selectpicker'){
        $this->ci =& get_instance();
        $this->ci->load->helper('form');
        $obtieneAtributos = ($atributos !== FALSE) ? $atributos : '';
        switch($tipoSeleccion){
            case 'search': $obtieneTipoSeleccion = 'data-live-search="true"'; break;
            case 'none': $obtieneTipoSeleccion = ''; break;
            default: $obtieneTipoSeleccion = ''; break;
        }
        if( (!is_null($texto)) && ($texto !== FALSE)){
            $opciones[''] = $texto;
        }
        foreach($datos as $k => $v){
            $opciones[$k] = $v;
        }
        switch($skin){
            case 'multiple': $infoExtra = 'id="'.$nombreImput.'" class="form-control selectpicker" multiple data-selected-text-format="count"' . $obtieneTipoSeleccion.' '.$obtieneAtributos; 
                break;
            case 'selectpicker': $infoExtra = 'id="'.$nombreImput.'" class="form-control selectpicker" data-container="body" '.$obtieneTipoSeleccion.' '.$obtieneAtributos; break;
            case 'select': $infoExtra = 'id="'.$nombreImput.'" data-plugin="customselect" class="form-control" '.$obtieneTipoSeleccion.' '.$obtieneAtributos; break;
            case 'simple': $infoExtra = 'id="'.$nombreImput.'" class="" '.$obtieneAtributos; break;
            default: $infoExtra = 'id="'.$nombreImput.'" class="form-control selectpicker" data-container="body" '.$obtieneTipoSeleccion.' '.$obtieneAtributos; break;
        }
        $generaDesplegable = form_dropdown($nombreImput, $opciones, $valorDefecto, $infoExtra);
        unset($opciones);
        return $generaDesplegable;
    }

    public function facebookComment($url = ''){
        $string = '';
        if($url == ''){
            $obtieneUrl = 'https://developers.facebook.com/docs/plugins/comments#configurator';
        } else{
            $obtieneUrl = $url;
        }
        $string .= '<div id="fb-root"></div>';
        $string .= '<script>(function(d, s, id) {';
        $string .= 'var js, fjs = d.getElementsByTagName(s)[0];';
        $string .= 'if (d.getElementById(id)) return;';
        $string .= 'js = d.createElement(s); js.id = id;';
        $string .= 'js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.5";';
        $string .= 'fjs.parentNode.insertBefore(js, fjs);';
        $string .= '}(document, \'script\', \'facebook-jssdk\'));</script>';
        $string .= '<div class="fb-comments" data-href="'.$obtieneUrl.'" data-width="100%" data-numposts="5"></div>';
        return $string;
    }


    public function generaTextoAmigable($nombre, $separador = '-') {
        $search = array(
            chr(192), chr(193), chr(194), chr(195), chr(224), chr(225), chr(226), chr(227), // a
            chr(201), chr(202), chr(233), chr(234), // e
            chr(205), chr(237), // i
            chr(211), chr(212), chr(213), chr(243), chr(244), chr(245), // o
            chr(218), chr(220), chr(250), chr(252), // u
            chr(199), chr(231), // c
            chr(209), chr(241) // ñ
        );
        $replace = array(
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e',
            'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u',
            'c', 'c',
            'n', 'n'
        );
        $aux = strtolower(str_replace($search, $replace, $nombre));
        $aux = preg_replace('/[^a-z0-9]/', $separador, $aux);
        return $aux;
    }

    public function datepicker($tipoCalendario, $infoCalendario, $cssClase, $rangoAnos = '1900:+0', $minFecha = '', $maxFecha = ''){
        /* CONTINUE */
        $datos = array(
            'direction' => TRUE,
            'showOtherMonths' => TRUE,
            'selectOtherMonths' => TRUE,
            'changeMonth' => TRUE,
        );
        $opciones = array();
        switch($tipoCalendario){
            case 'defecto':
                $opciones = array(
                );
                $opciones = array_merge($opciones, $infoCalendario);
                break;
            case 'estandar':
                $opciones = array(
                    'changeYear' => TRUE,
                    'yearRange' => $rangoAnos
                );
                $opciones = array_merge($opciones, $infoCalendario);
                break;
            case 'nacimiento':
                $opciones = array(
                    'changeYear' => TRUE,
                    'yearRange' => $rangoAnos,
                    'maxDate' => $maxFecha
                );
                $opciones = array_merge($opciones, $infoCalendario);
                break;
            case 'futuro':
                $opciones = array(
                    'changeYear' => TRUE,
                    'minDate' => $minFecha
                );
                $opciones = array_merge($opciones, $infoCalendario);
                break;
        }
        $generaDatos = array_merge($datos, $opciones);
        $resultado = array(
            'jquery' => json_encode($generaDatos, JSON_NUMERIC_CHECK),
            'clases' => $cssClase
        );
        return $resultado;
    }

    public function addSlashtag($string, $openTag = '»', $closeTag = '«'){
        $stmString = str_replace('"', "'", $string);
        $count = mb_substr_count($stmString, "'");
        $temp = $stmString;
        for($i=1; $i<=$count; $i++){
            if(($i % 2) == 0){
                $temp = preg_replace(array("/'/"), array($closeTag), $temp, 1);
            } else{
                $temp = preg_replace(array("/'/"), array($openTag), $temp, 1);
            }
        }
        return $temp;
    }

}
