<?php
class Talla {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerTallas() {
        $query = "SELECT * FROM tallas";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertarTalla($data) {
        $query = "INSERT INTO tallas (talla) VALUES (:talla)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':talla', $data['talla']);
        return $stmt->execute();
    }

    public function actualizarTalla($id, $data) {
        $query = "UPDATE tallas SET talla = :talla WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':talla', $data['talla']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function eliminarTalla($id) {
        $query = "DELETE FROM tallas WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
