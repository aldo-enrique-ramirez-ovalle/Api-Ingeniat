<?php
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
    case 'consultaDePublicaciones':
            $bearerToken = getBearerToken();
            if ($bearerToken == null) {
                $res['msjError'] = 'No se encontraron coincidencias de token';
            }else{
                $oAuthJWT = new AuthJWT();
                try{
                    $dataToken = $oAuthJWT->GetData($bearerToken);
                    $res['msjError'] = "Acceso denegado para Consultar publicaciones";
                }catch(Exception $e){
                    $res['msjError'] = "El Token es inválido, ha sido alterado o ha caducado.";
                }
                if ($dataToken->Rol == 2 ||
                    $dataToken->Rol == 4 ||
                    $dataToken->Rol == 5) {
                        $SPModel->setORdb($oRdb);                        
                        $SPModel->consultaDePublicaciones();                        
                        $res = $SPModel->getRespuesta();
                        $SPModel->getRegistros();
                        unset($res['msjError']);
                }
            }
            echo json_encode($res);
	break;
    default:
        $arrayAccion['msjAccion'] = 'Allow: Login, consultaDePublicaciones';
        header('HTTP/1.1 405 Accion Not Allowed');
        echo json_encode($arrayAccion);
    break;
}
?>
