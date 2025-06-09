<?php

class Camiseta {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insertarCamiseta($data) {
        try {
            error_log("Datos recibidos en insertarCamiseta: " . json_encode($data)); //depuración

            $query = "INSERT INTO camisetas 
                      (titulo, club, pais, tipo, color, precio_base, detalles, sku)
                      VALUES 
                      (:titulo, :club, :pais, :tipo, :color, :precio_base, :detalles, :sku)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':titulo', $data['titulo']);
            $stmt->bindParam(':club', $data['club']);
            $stmt->bindParam(':pais', $data['pais']);
            $stmt->bindParam(':tipo', $data['tipo']);
            $stmt->bindParam(':color', $data['color']);
            $stmt->bindParam(':precio_base', $data['precio_base']);
            $stmt->bindParam(':detalles', $data['detalles']);
            $stmt->bindParam(':sku', $data['sku']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al insertar camiseta: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerCamisetas() {
        try {
            $query = "SELECT * FROM camisetas";
            $stmt = $this->conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener camisetas: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerCamisetaPorId($id) {
        try {
            $query = "SELECT * FROM camisetas WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener camiseta por ID: " . $e->getMessage());
            return null;
        }
    }

    public function actualizarCamiseta($id, $data) {
    try {
        error_log("Datos recibidos para actualizarCamiseta: ID=$id, " . json_encode($data)); // depuración

        $query = "UPDATE camisetas SET 
                    titulo = COALESCE(:titulo, titulo),
                    club = COALESCE(:club, club),
                    pais = COALESCE(:pais, pais),
                    tipo = COALESCE(:tipo, tipo),
                    color = COALESCE(:color, color),
                    precio_base = COALESCE(:precio_base, precio_base),
                    detalles = COALESCE(:detalles, detalles),
                    sku = COALESCE(:sku, sku)
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':id', $id);

        // Campos con control de isset para evitar warnings y null accidentales
        $stmt->bindValue(':titulo', isset($data['titulo']) ? $data['titulo'] : null);
        $stmt->bindValue(':club', isset($data['club']) ? $data['club'] : null);
        $stmt->bindValue(':pais', isset($data['pais']) ? $data['pais'] : null);
        $stmt->bindValue(':tipo', isset($data['tipo']) ? $data['tipo'] : null);
        $stmt->bindValue(':color', isset($data['color']) ? $data['color'] : null);
        $stmt->bindValue(':precio_base', isset($data['precio_base']) ? $data['precio_base'] : null);
        $stmt->bindValue(':detalles', isset($data['detalles']) ? $data['detalles'] : null);
        $stmt->bindValue(':sku', isset($data['sku']) ? $data['sku'] : null);

        $resultado = $stmt->execute();
        error_log("Resultado de la ejecución: " . ($resultado ? "Éxito" : "Fallo"));
        return $resultado;
    } catch (PDOException $e) {
        error_log("Error al actualizar camiseta: " . $e->getMessage());
        return false;
    }
}

    public function eliminarCamiseta($id) {
        try {
            $query = "DELETE FROM camisetas WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al eliminar camiseta: " . $e->getMessage());
            return false;
        }
    }

    //Logica de descuentos y precios finales
    public function obtenerCamisetasConPrecioFinal($cliente_id) {
    try {
        $query = "
            SELECT c.*, 
                   CASE 
                       WHEN cl.categoria = 'preferencial' AND o.precio_oferta IS NOT NULL THEN o.precio_oferta
                       ELSE c.precio_base
                   END AS precio_final
            FROM camisetas c
            LEFT JOIN ofertas o ON c.id = o.camiseta_id AND o.cliente_id = :cliente_id
            LEFT JOIN clientes cl ON cl.id = :cliente_id
        ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Error al obtener camisetas con precio final: " . $e->getMessage());
        return [];
    }
}

}
?>

