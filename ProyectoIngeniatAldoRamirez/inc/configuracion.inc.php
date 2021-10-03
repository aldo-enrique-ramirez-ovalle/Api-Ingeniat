<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
    $Folder		= 'ProyectoIngeniatAldoRamirez';
    $Modelo		= $_SERVER['DOCUMENT_ROOT']."/".$Folder."/model/*.class.php";
    $Controlador	= $_SERVER['DOCUMENT_ROOT']."/".$Folder."/controller/*.class.php";
    $Clases       = $_SERVER['DOCUMENT_ROOT']."/".$Folder."/class/*.class.php";
        foreach(glob($Clases) as $filename){ 
        	include $filename;
        }
        foreach(glob($Controlador) as $filename){
        	include $filename;
        }
        foreach(glob($Modelo) as $filename){
            include $filename;
        }
    $ip		= getIP();
    $oLog	= new Log($ip, 'ingeniatpublicaciones');
    $Config_Lectura = array(
    	'Servidor'			=> '127.0.0.1',
        'BaseDeDatos'       => 'ingeniatpublicaciones',
        'Puerto'            => '3306',
    	'Usuario'			=> 'root',
    	'Contrasena'		=> '',    	
    	'TipoObjeto'	=> '',
    	'oLog'			=> $oLog    	
    );
    $Config_Escritura = array(
    	'Servidor'			=> '127.0.0.1',
        'BaseDeDatos'       => '',
        'Puerto'            => '3306',
    	'Usuario'			=> 'root',
    	'Contrasena'		=> '',    	
    	'TipoObjeto'	=> '',
    	'oLog'			=> $oLog    	
    );
    $oRdb = new My_SQLI($Config_Lectura);
    $oWdb = new My_SQLI($Config_Escritura);
    $oRdb->setBDebug(0);
    $oWdb->setBDebug(0);
        if(!$oRdb->bConectado || !$oWdb->bConectado){
        	echo "NO FUE POSIBLE CONECTAR LA BASE DE DATOS";exit();
        }
    function getIP(){
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $ip_array = explode(",", $ip);
        $ip = trim($ip_array[0]);
        return $ip;
    }
    function utf8ize($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = utf8ize($v);
            }
        } else if (is_string ($d)) {
            return utf8_encode($d);
        }
        return $d;
    }
    function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { 
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    function getBearerToken() {
        $headers = getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
?>