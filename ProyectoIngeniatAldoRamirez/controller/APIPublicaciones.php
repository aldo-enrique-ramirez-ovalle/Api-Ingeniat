<?php
include "../inc/configuracion.inc.php";
require_once '../vendor/autoload.php';
    $SPModel = new SP_model();
    $contErrorIni = 0;
    $MGeneral['Content_typeInvalid'] = "";
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if(strcasecmp($contentType, 'application/json') != 0){
            $contErrorIni++;
            $MGeneral['Content_typeInvalid'] = 'El tipo de contenido debe ser: application/json';
        }
    $content = trim(file_get_contents("php://input"));
    $decoded = json_decode($content, true);
        if(!is_array($decoded)){
            $contErrorIni ++;
            $MGeneral['JsonInvalid'] = 'El contenido recibido contiene JSON no valido!';
        }
        if ($contErrorIni > 0) {
            echo json_encode($MGeneral);
        }
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            include_once 'Post.php'; 
        break;
        case 'DELETE':
            include_once 'Delete.php';  
        break;
        case 'GET':
            include_once 'Get.php';        
        break;
        case 'PUT':
            include_once 'Put.php'; 
        break;
        default:
                $arrayAccion['msjAccion'] = 'Allow: POST, PUT, GET, DELETE';
                header('HTTP/1.1 405 Method Not Allowed');
                echo json_encode($arrayAccion);
        break;
    }
?>