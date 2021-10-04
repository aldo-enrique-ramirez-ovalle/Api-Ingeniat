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
        case 'DELETE':
            $opcionDELETE = $decoded['accion'];
            switch ($opcionDELETE) {
                case 'eliminacionDePublicacionLogica':
                    $bearerToken = getBearerToken();
                    if ($bearerToken == null) {
                        $res['msjError'] = 'No se encontraron coincidencias de token';
                    }else{
                        $oAuthJWT = new AuthJWT();
                        try{
                            $dataToken = $oAuthJWT->GetData($bearerToken);
                            $res['msjError'] = "Acceso denegado para Eliminar publicaciones";
                        }catch(Exception $e){
                            $res['msjError'] = "El Token es inválido, ha sido alterado o ha caducado."; 
                        }
                        if ($dataToken->Rol == 5) {
                                $SPModel->setORdb($oRdb);
                                $SPModel->setIdPublicacion($decoded['IdPublicacion']);
                                $SPModel->setIdUser($dataToken->IdUser);                   
                                $SPModel->eliminacionDePublicacionLogica();                    
                                $res = $SPModel->getRespuesta();
                                $SPModel->getRegistros();
                                unset($res['msjError']);
                        }
                    }
                    echo json_encode($res);
                break;
                default:
                    $arrayAccion['msjAccion'] = 'Allow: eliminacionDePublicacionLogica';
                    header('HTTP/1.1 405 Accion Not Allowed');
                    echo json_encode($arrayAccion);
                break;
            }
        break;
       
        default:
                $arrayAccion['msjAccion'] = 'Allow:  DELETE';
                header('HTTP/1.1 405 Method Not Allowed');
                echo json_encode($arrayAccion);
        break;
    }
?>