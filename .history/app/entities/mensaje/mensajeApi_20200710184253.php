<?php
require_once 'mensaje.php';
require_once 'IApiCRUD.php';

class mensajeApi extends mensaje implements IApiCRUD {
	
	public function readAllApi($request, $response, $args) {
		$all=mensaje::readAll();
	   	$response = $response->withJson($all, 200);  
		  
		return $response;
	}

	public function readApi($request, $response, $args) {
		$id=$args['id'];
		$art=mensaje::read($id);
		$newResponse = $response->withJson($art, 200);  
		
		return $newResponse;
	}


	public function CreateApi($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();

		$entity = new mensaje();
		//$entity->id_pedido_item = $ArrayDeParametros['id_pedido_item'];
		$entity->nombre = $ArrayDeParametros['nombre'];
     	$entity->email = $ArrayDeParametros['email'];
		$entity->telefono = $ArrayDeParametros['telefono'];
		$entity->mensaje = $ArrayDeParametros['mensaje'];
		$entity->estado = $ArrayDeParametros['estado'];
		  
		$response = $entity->create();

	  	return $response;
	}

	
	
	
	/*
	public $idMensaje;
	public $nombre;
	public $email;
	public $telefono;
	public $mensaje;
	public $estado;
	*/


	public function updateApi($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		
		$entity = new mensaje();
		$entity->idMensaje = $ArrayDeParametros['idMensaje'];
		$entity->nombre = $ArrayDeParametros['nombre'];
		$entity->email = $ArrayDeParametros['email'];
		$entity->telefono = $ArrayDeParametros['telefono'];
		$entity->mensaje = $ArrayDeParametros['mensaje'];
		$entity->estado = $ArrayDeParametros['estado'];

		$resultado = $entity->update();
		
        $objDelaRespuesta = new stdclass();
		$objDelaRespuesta->resultado = $resultado;
		
        return $response->withJson($objDelaRespuesta, 200);
	}
	
	public function deleteApi($request, $response, $args) {
        $id = $args["id"];
        $respuesta = mensaje::delete($id);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
	}
}





