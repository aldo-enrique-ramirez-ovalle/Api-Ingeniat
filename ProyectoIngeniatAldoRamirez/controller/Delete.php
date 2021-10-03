<?php
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
?>