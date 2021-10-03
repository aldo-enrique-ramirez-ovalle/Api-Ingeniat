<?php
$opcPOST = $decoded['accion'];
switch ($opcPOST) {                
    case 'registroDeUsuario':
       $bearerToken = getBearerToken();
        if ($bearerToken == null) {
            $res['msjError'] = 'No se encontraron coincidencias de token';
        }else{
            $oAuthJWT = new AuthJWT();
            try{
                $dataToken = $oAuthJWT->GetData($bearerToken);
                $res['msjError'] = "Acceso denegado para Registrar usuarios";
            }catch(Exception $e){
                $res['msjError'] = "El Token es inválido, ha sido alterado o ha caducado. "; 
            }
            if ($dataToken->Rol == 5 || 
                $dataToken->Rol == 4 || 
                $dataToken->Rol == 3) {
                    $SPModel->setORdb($oRdb);
                    $SPModel->setNombre($decoded['Nombre']);
                    $SPModel->setApellidoPaterno($decoded['Apellido']);
                    $SPModel->setEmail($decoded['Correo']);
                    $SPModel->setContrasena($decoded['Password']);
                    $SPModel->setRol($decoded['Rol']);
                    $SPModel->registroDeUsuario();                    
                    $res = $SPModel->getRespuesta();
                    $SPModel->getRegistros();
                    unset($res['msjError']);
            }
        }
        echo json_encode($res);        
    break;
    case 'creacionDePublicacion':
        $bearerToken = getBearerToken();
        if ($bearerToken == null) {
            $res['msjError'] = 'No se encontraron coincidencias de token';
        }else{
            $oAuthJWT = new AuthJWT();
            try{
                $dataToken = $oAuthJWT->GetData($bearerToken);
                $res['msjError'] = "Acceso denegado para Crear publicaciones";
            }catch(Exception $e){
                $res['msjError'] = "El Token es inválido, ha sido alterado o ha caducado."; 
            }
            if ($dataToken->Rol == 5 || 
                $dataToken->Rol == 4 || 
                $dataToken->Rol == 3) {*/
                    $SPModel->setORdb($oRdb);
                    $SPModel->setIdUser($dataToken->IdUser);
                    $SPModel->setTitulo($decoded['Titulo']);
                    $SPModel->setDescripcion($decoded['Descripcion']);                    
                    $SPModel->creacionDePublicacion();                    
                    $res = $SPModel->getRespuesta();
                    $SPModel->getRegistros();
                    unset($res['msjError']);
            }
        }

        echo json_encode($res);        
    break;
    default:
        $arrayAccion['msjAccion'] = 'Allow: registroDeUsuario, creacionDePublicacion';
        header('HTTP/1.1 405 Accion Not Allowed');
        echo json_encode($arrayAccion);
    break;
}
?>