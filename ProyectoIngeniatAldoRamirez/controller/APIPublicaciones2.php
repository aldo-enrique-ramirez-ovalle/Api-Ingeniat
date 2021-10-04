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
        case 'GET':
            $opcionGET = $decoded['accion'];
            switch ($opcionGET) {
                case 'Login':
                    $SPModel->setORdb($oRdb);
                    $SPModel->setEmail($decoded['Correo']);
                    $SPModel->setContrasena($decoded['Password']);
                    $SPModel->login();
                    $resLogin = $SPModel->getRespuesta();
                    $SPModel->getRegistros();
                    if($resLogin['Codigo'] == 0){
                        $oAuthJWT = new AuthJWT();
                        $token = $oAuthJWT->SignIn([
                                                    'IdUser' => $resLogin['IdUsuario'],
                                                    'Nombre' => $resLogin['Nombre'],
                                                    'Rol' => $resLogin['nIdRol']
                                                   ]);
                        $resLogin['token'] = $token;
                    }
                    unset($resLogin['IdUser'],$resLogin['nIdRol'],$resLogin['Nombre']);
                    echo json_encode($resLogin);
                break;
               
                default:
                    $arrayAccion['msjAccion'] = 'Allow: Login';
                    header('HTTP/1.1 405 Accion Not Allowed');
                    echo json_encode($arrayAccion);
                break;
            }
        break;
        
        default:
                $arrayAccion['msjAccion'] = 'Allow:  GET';
                header('HTTP/1.1 405 Method Not Allowed');
                echo json_encode($arrayAccion);
        break;
    }
?>