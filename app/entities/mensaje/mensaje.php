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
			$consulta->bindValue(':mensaje', $this->mensaje, PDO::PARAM_STR);
			$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);

			$consulta->execute();

        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => "ERROR", "Mensaje" => "$mensaje");
        } finally {
            return $objetoAccesoDato->RetornarUltimoIdInsertado();
        }
	}

	public function update() {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta(
				"UPDATE `mensajes` SET 
				`nombre` = :nombre,
				`email` = :email,  
				`telefono` = :telefono, 
				`mensaje` = :mensaje,
				`estado` = :estado
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
				"DELETE FROM `mensajes` 
				WHERE `idMensaje` = $id"
				);
            $consulta->bindValue(':idMensaje', $id, PDO::PARAM_STR);
            $consulta->execute();
            $respuesta = array("Estado" => true, "Mensaje" => "Eliminado Correctamente");

        } catch (Exception $e) {
            $mensaje = $e->getMessage();
            $respuesta = array("Estado" => false, "Mensaje" => "$mensaje");

        } finally {
            return $respuesta;
        }
	}
}

