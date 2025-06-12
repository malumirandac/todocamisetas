<?php
class CamisetaTalla {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertar($data) {
        $query = "INSERT INTO camiseta_talla (camiseta_id, talla_id, cantidad) VALUES (:camiseta_id, :talla_id, :cantidad)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':camiseta_id', $data['camiseta_id']);
        $stmt->bindParam(':talla_id', $data['talla_id']);
        $stmt->bindParam(':cantidad', $data['cantidad']);
        return $stmt->execute();
    }

    public function obtenerTodos() {
        $query = "
            SELECT ct.id, c.titulo AS camiseta, t.talla, ct.cantidad
            FROM camiseta_talla ct
            JOIN camisetas c ON ct.camiseta_id = c.id
            JOIN tallas t ON ct.talla_id = t.id";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizar($id, $data) {
        $query = "UPDATE camiseta_talla SET cantidad = :cantidad WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cantidad', $data['cantidad']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $query = "DELETE FROM camiseta_talla WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
