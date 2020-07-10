<?php
class mensaje
{
	public $idMensaje;
	public $nombre;
	public $email;
	public $telefono;
	public $mensaje;
	public $estado;

  	public static function readAll () {
		try {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta = $objetoAccesoDato->RetornarConsulta(
				"SELECT * FROM `mensajes` WHERE 1"
			);
			$consulta->execute();
					
			$ret =  $consulta->fetchAll(PDO::FETCH_CLASS, "mensaje");
		} catch (Exception $e) {
			$mensaje = $e->getMessage();
			$respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
		} finally {
			return $ret;
		}		
	}

	public static function read($id) {
		try {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta = $objetoAccesoDato->RetornarConsulta(
				"SELECT * FROM `mensajes` WHERE `idMensaje` = $id"
			);
			$consulta->execute();
			$ret = $consulta->fetchObject("mensaje");
        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        } finally {
            return $ret;
        }
	}

	public function create() {
		try {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta(
				"INSERT INTO `mensajes`
				(`nombre`, `email`, `telefono`, `mensaje`, `estado`)
				VALUES
				(:nombre, :email, :telefono, :mensaje, :estado)"
			);
			// $consulta->bindValue(':id_pedido_item', $this->id_pedido_item, PDO::PARAM_INT); ai
			$consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
			$consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
			$consulta->bindValue(':telefono', $this->telefono, PDO::PARAM_STR);
			$consulta->bindValue(':mensaje', $this->precio_lmensajeista, PDO::PARAM_STR);
			$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);

			$consulta->execute();

        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        } finally {
            return $objetoAccesoDato->RetornarUltimoIdInsertado();
        }
	}

	
	/*
	public $idMensaje;
	public $nombre;
	public $email;
	public $telefono;
	public $mensaje;
	public $estado;
	*/

	public function update() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta(
				"UPDATE `mensajes` SET 
				`nombre` = :nombre,
				`email` = :email,  
				`idArticulo` = :idArticulo, 
				`precio_lista` = :precio_lista,
				`cantidad` = :cantidad
				WHERE `idMensaje` = :idMensaje");
                
			$consulta->bindValue(':idMensaje', $this->idMensaje, PDO::PARAM_STR);
			$consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
			$consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
			$consulta->bindValue(':telefono', $this->telefono, PDO::PARAM_STR);
			$consulta->bindValue(':mensaje', $this->mensaje, PDO::PARAM_STR);
			$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);

        return $consulta->execute();
	}
	
	public static function delete($id) {
        try {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta(
				"DELETE FROM `pedidos_item` 
				WHERE `idPedidoItem` = $id"
				);
            $consulta->bindValue(':idPedidoItem', $id, PDO::PARAM_STR);
            $consulta->execute();
            $respuesta = array("Estado" => true, "Mensaje" => "Eliminado Correctamente");

        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => false, "Mensaje" => "$mensaje");

        } finally {
            return $respuesta;
        }
	}

	public static function readAllCliente ($id) {
		try {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta = $objetoAccesoDato->RetornarConsulta(
				"SELECT 
				p.idPedidoItem,
				p.idPedido,  
				p.idCliente, 
				p.idArticulo, 
				p.cantidad, 
				a.descripcion_corta, 
				a.stock, 
				p.precio_lista
				FROM articulos a, pedidos_item p
				WHERE a.id_articulo = p.idArticulo 
				AND p.idCliente = $id"
			);
			$consulta->execute();
					
			$ret =  $consulta->fetchAll(PDO::FETCH_CLASS, "pedido_item");
		} catch (Exception $e) {
			$mensaje = $e->getMessage();
			$respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
		} finally {
			return $ret;
		}		
	}
/**
 * id_pedido -1 significa que no pertenece a ningun pedido
 * es decir, falta cerrar el pedido y se reemplaza con el numero de pedido
 * generado una vez que el cliente cierra el pedido
 */
	public static function readAllClienteAbierto ($id) {
		try {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta = $objetoAccesoDato->RetornarConsulta(
				"SELECT p.idPedidoItem, p.idCliente, p.idPedido, p.idArticulo, p.cantidad, a.descripcion_corta, a.stock, p.precio_lista
				FROM articulos a, pedidos_item p
				WHERE a.id_articulo = p.idArticulo 
				AND p.idCliente = $id 
				AND p.idPedido = -1"
			);
			$consulta->execute();
					
			$ret =  $consulta->fetchAll(PDO::FETCH_CLASS, "pedido_item");
		} catch (Exception $e) {
			$mensaje = $e->getMessage();
			$respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
		} finally {
			return $ret;
		}		
	}

	public function updateItems($idPedido, $idCliente){
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta(
			"UPDATE `pedidos_item` 
			SET `idPedido`= $idPedido 
			WHERE `idCliente` = '$idCliente'
			AND `idPedido` = -1");

        return $consulta->execute();
	}
}

