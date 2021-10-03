<?php
$opcionPUT = $decoded['accion'];
switch ($opcionPUT) {
    case 'actualizacionDePublicacion':
        $bearerToken = getBearerToken();
        if ($bearerToken == null) {
            $res['msjError'] = 'No se encontraron coincidencias de token';
        }else{
            $oAuthJWT = new AuthJWT();
            try{
                $dataToken = $oAuthJWT->GetData($bearerToken);
                $res['msjError'] = "Acceso denegado para Actualizar publicaciones";
                }catch(Exception $e){
                    $res['msjError'] = "El Token es inválido, ha sido alterado o ha caducado.";
                }            
            if ($dataToken->Rol == 5 || 
                $dataToken->Rol == 4) {
                    $SPModel->setORdb($oRdb);
                    $SPModel->setIdPublicacion($decoded['IdPublicacion']);
                    $SPModel->setIdUser($dataToken->IdUser);
                    $SPModel->setTitulo($decoded['Titulo']);
                    $SPModel->setDescripcion($decoded['Descripcion']);                    
                    $SPModel->actualizacionDePublicacion();                    
                    $res = $SPModel->getRespuesta();
                    $SPModel->getRegistros();
                    unset($res['msjError']);
            }
        }
        echo json_encode($res);
    break;
    default:
        $arrayAccion['msjAccion'] = 'Allow: actualizacionDePublicacion';
        header('HTTP/1.1 405 Accion Not Allowed');
        echo json_encode($arrayAccion);
    break;
}
?>