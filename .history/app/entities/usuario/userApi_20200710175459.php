<?php
require_once 'user.php';

class userApi extends user {

	public function readAllApi($request, $response, $args){
        $all = user::readAll();
        $newResponse = $response->withJson($all, 200);
        return $newResponse;
    }

    public function readApi($request, $response, $args){
        $id = $args['id_user'];
        $Ret = user::read($id);
        $newResponse = $response->withJson($Ret, 200);
        return $newResponse;
    }

    public function CreateApi($request, $response, $args){
        $ArrayDeParametros = $request->getParsedBody();

        $entity = new user();
        $entity->id_user = $ArrayDeParametros['id_user'];
        $entity->nombre = $ArrayDeParametros['nombre'];
        $entity->apellido = $ArrayDeParametros['apellido'];
        $entity->usuario = $ArrayDeParametros['usuario'];
        $entity->password = $ArrayDeParametros['password'];
        $entity->estado = $ArrayDeParametros['estado'];
        $entity->rol = $ArrayDeParametros['rol'];

        $entity->create();

        $response->getBody()->write("true");

        return $response;
    }

    public function deleteApi($request, $response, $args){
        $id = $args["id_user"];
        $respuesta = user::delete($id);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }

    public function updateApi($request, $response, $args){
        $ArrayDeParametros = $request->getParsedBody();

        $entity = new user();
        $entity->id_user = $ArrayDeParametros['id_user'];
        $entity->nombre = $ArrayDeParametros['nombre'];
        $entity->apellido = $ArrayDeParametros['apellido'];
        $entity->usuario = $ArrayDeParametros['usuario'];
        $entity->password = $ArrayDeParametros['password'];
        $entity->estado = $ArrayDeParametros['estado'];
        $entity->rol = $ArrayDeParametros['rol'];

        $resultado = $entity->update();

        $objDelaRespuesta = new stdclass();
        $objDelaRespuesta->resultado = $resultado;

        return $response->withJson($objDelaRespuesta, 200);
    }

	public function LoginUser($request, $response, $args){
        $json = $request->getBody();
		$data = json_decode($json, true);

		$retorno = user::Login($data["usuario"], $data["password"]);

        if ($retorno["usuario"] != "") {
            $respuesta = array("Estado" => true, "Mensaje" => "Logueado Exitosamente");
        } else {
            $respuesta = array("Estado" => false, "Mensaje" => "Usuario o Clave Invalidos");
        }
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
    }
}
?>




