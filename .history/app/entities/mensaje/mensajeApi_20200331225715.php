<?php
require_once 'pedido_item.php';
require_once 'IApiCRUD.php';

class pedido_itemApi extends pedido_item implements IApiCRUD {
	
	public function readAllApi($request, $response, $args) {
		$all=pedido_item::readAll();
	   	$response = $response->withJson($all, 200);  
		  
		return $response;
	}

	public function readApi($request, $response, $args) {
		$id=$args['id'];
		$art=pedido_item::read($id);
		$newResponse = $response->withJson($art, 200);  
		
		return $newResponse;
	}

	public function CreateApi($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();

		$entity = new pedido_item();
		//$entity->id_pedido_item = $ArrayDeParametros['id_pedido_item'];
		$entity->idPedido = $ArrayDeParametros['idPedido'];
     	$entity->idCliente = $ArrayDeParametros['idCliente'];
		$entity->idArticulo = $ArrayDeParametros['idArticulo'];
		$entity->precio_lista = $ArrayDeParametros['precio_lista'];
		$entity->cantidad = $ArrayDeParametros['cantidad'];
		  
		$response = $entity->create();

	  	return $response;
	}

	public function updateApi($request, $response, $args) {
		$ArrayDeParametros = $request->getParsedBody();
		
		$entity = new pedido_item();
		$entity->idPedidoItem = $ArrayDeParametros['idPedidoItem'];
		$entity->idPedido = $ArrayDeParametros['idPedido'];
		$entity->idCliente = $ArrayDeParametros['idCliente'];
		$entity->idArticulo = $ArrayDeParametros['idArticulo'];
		$entity->precio_lista = $ArrayDeParametros['precio_lista'];
		$entity->cantidad = $ArrayDeParametros['cantidad'];

		$resultado = $entity->update();
		
        $objDelaRespuesta = new stdclass();
		$objDelaRespuesta->resultado = $resultado;
		
        return $response->withJson($objDelaRespuesta, 200);
	}
	
	public function deleteApi($request, $response, $args) {
        $id = $args["id"];
        $respuesta = pedido_item::delete($id);
        $newResponse = $response->withJson($respuesta, 200);
        return $newResponse;
	}
	
	public function readAllClienteApi ($request, $response, $args) {
        $id = $args["id"];
		$all=pedido_item::readAllCliente($id);
		$newResponse = $response->withJson($all, 200);

		return $newResponse;
	}

	public function readAllClienteAbiertoApi ($request, $response, $args) {
        $id = $args["id"];
		$all=pedido_item::readAllClienteAbierto($id);
		$newResponse = $response->withJson($all, 200);

		return $newResponse;
	}

	public function updateItemsApi($request, $response, $args){
		
		$ArrayDeParametros = $request->getParsedBody();

		$id_pedido=$ArrayDeParametros['idPedido'];
		$id_cliente=$ArrayDeParametros['idCliente'];

		$all=pedido_item::updateItems($id_pedido, $id_cliente);
		$newResponse = $response->withJson($all, 200);

		return $newResponse;
	}
}





