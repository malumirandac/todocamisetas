<?php

class Cliente {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    
    public function insertarCliente($data) {
        try {
            error_log("Datos recibidos en insertarCliente: " . json_encode($data));

            $query = "INSERT INTO clientes 
                      (nombre_comercial, rut, ciudad, region, categoria, contacto_nombre, contacto_correo, porcentaje_descuento)
                      VALUES 
                      (:nombre_comercial, :rut, :ciudad, :region, :categoria, :contacto_nombre, :contacto_correo, :porcentaje_descuento)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindValue(':nombre_comercial', $data['nombre_comercial']);
            $stmt->bindValue(':rut', $data['rut']);
            $stmt->bindValue(':ciudad', $data['ciudad']);
            $stmt->bindValue(':region', $data['region']);
            $stmt->bindValue(':categoria', $data['categoria']);
            $stmt->bindValue(':contacto_nombre', $data['contacto_nombre']);
            $stmt->bindValue(':contacto_correo', $data['contacto_correo']);
            $stmt->bindValue(':porcentaje_descuento', $data['porcentaje_descuento']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al insertar cliente: " . $e->getMessage());
            return false;
        }
    }

    
    public function obtenerClientes() {
        try {
            $query = "SELECT * FROM clientes";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener clientes: " . $e->getMessage());
            return [];
        }
    }

    
    public function obtenerClientePorId($id) {
        try {
            $query = "SELECT * FROM clientes WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener cliente por ID: " . $e->getMessage());
            return null;
        }
    }

    
    public function actualizarCliente($id, $data) {
        try {
            error_log("Datos recibidos para actualizarCliente: ID=$id, " . json_encode($data));

            $query = "UPDATE clientes SET 
                        nombre_comercial = COALESCE(:nombre_comercial, nombre_comercial),
                        rut = COALESCE(:rut, rut),
                        ciudad = COALESCE(:ciudad, ciudad),
                        region = COALESCE(:region, region),
                        categoria = COALESCE(:categoria, categoria),
                        contacto_nombre = COALESCE(:contacto_nombre, contacto_nombre),
                        contacto_correo = COALESCE(:contacto_correo, contacto_correo),
                        porcentaje_descuento = COALESCE(:porcentaje_descuento, porcentaje_descuento)
                      WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':nombre_comercial', isset($data['nombre_comercial']) ? $data['nombre_comercial'] : null);
            $stmt->bindValue(':rut', isset($data['rut']) ? $data['rut'] : null);
            $stmt->bindValue(':ciudad', isset($data['ciudad']) ? $data['ciudad'] : null);
            $stmt->bindValue(':region', isset($data['region']) ? $data['region'] : null);
            $stmt->bindValue(':categoria', isset($data['categoria']) ? $data['categoria'] : null);
            $stmt->bindValue(':contacto_nombre', isset($data['contacto_nombre']) ? $data['contacto_nombre'] : null);
            $stmt->bindValue(':contacto_correo', isset($data['contacto_correo']) ? $data['contacto_correo'] : null);
            $stmt->bindValue(':porcentaje_descuento', isset($data['porcentaje_descuento']) ? $data['porcentaje_descuento'] : null);

            $resultado = $stmt->execute();
            error_log("Resultado de la ejecución: " . ($resultado ? "Éxito" : "Fallo"));
            return $resultado;
        } catch (PDOException $e) {
            error_log("Error al actualizar cliente: " . $e->getMessage());
            return false;
        }
    }

    
    public function eliminarCliente($id) {
        try {
            $query = "DELETE FROM clientes WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar cliente: " . $e->getMessage());
            return false;
        }
    }
}
?>
