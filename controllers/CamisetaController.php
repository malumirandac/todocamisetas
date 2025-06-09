<?php
// controllers/CamisetaController.php
require_once './models/Camiseta.php';

class CamisetaController {
    private $camiseta;

    public function __construct($db) {
        $this->camiseta = new Camiseta($db);
    }

    public function insertarCamiseta($data) {
        // Validar campos obligatorios
        if (!empty($data['titulo']) && !empty($data['club']) && !empty($data['pais']) &&
            !empty($data['tipo']) && !empty($data['color']) &&
            isset($data['precio_base']) && !empty($data['sku'])) {

            $resultado = $this->camiseta->insertarCamiseta($data);

            if ($resultado) {
                http_response_code(201);
                echo json_encode(["mensaje" => "Camiseta insertada correctamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["mensaje" => "No se pudo insertar la camiseta"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "Faltan campos requeridos"]);
        }
    }

    public function obtenerCamisetas() {
        try {
            $camisetas = $this->camiseta->obtenerCamisetas();
            if (!empty($camisetas)) {
                http_response_code(200);
                echo json_encode($camisetas);
            } else {
                http_response_code(404);
                echo json_encode(["mensaje" => "No se encontraron camisetas"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["mensaje" => "Error al obtener camisetas", "detalle" => $e->getMessage()]);
        }
    }

    public function obtenerCamisetaPorId($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de camiseta no proporcionado"]);
            return;
        }

        $camiseta = $this->camiseta->obtenerCamisetaPorId($id);

        if ($camiseta) {
            http_response_code(200);
            echo json_encode($camiseta);
        } else {
            http_response_code(404);
            echo json_encode(["mensaje" => "Camiseta no encontrada"]);
        }
    }

    public function actualizarCamiseta($id, $data) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de camiseta no proporcionado"]);
            return;
        }

        // Validar que al menos haya un campo que se intente actualizar
        if (!empty($data['titulo']) || !empty($data['club']) || !empty($data['pais']) ||
            !empty($data['tipo']) || !empty($data['color']) || isset($data['precio_base']) ||
            !empty($data['detalles']) || !empty($data['sku'])) {

            $resultado = $this->camiseta->actualizarCamiseta($id, $data);

            if ($resultado) {
                http_response_code(200);
                echo json_encode(["mensaje" => "Camiseta actualizada correctamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["mensaje" => "No se pudo actualizar la camiseta"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "No se proporcionaron datos para actualizar"]);
        }
    }

    public function actualizarParcialCamiseta($id, $data) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de camiseta no proporcionado"]);
            return;
        }

        if (!empty($data)) {
            $resultado = $this->camiseta->actualizarCamiseta($id, $data);

            if ($resultado) {
                http_response_code(200);
                echo json_encode(["mensaje" => "Camiseta actualizada parcialmente"]);
            } else {
                http_response_code(500);
                echo json_encode(["mensaje" => "No se pudo actualizar la camiseta"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "No se proporcionaron datos para actualizar"]);
        }
    }

    public function eliminarCamiseta($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de camiseta no proporcionado"]);
            return;
        }

        $resultado = $this->camiseta->eliminarCamiseta($id);
        if ($resultado) {
            http_response_code(200);
            echo json_encode(["mensaje" => "Camiseta eliminada correctamente"]);
        } else {
            http_response_code(500);
            echo json_encode(["mensaje" => "No se pudo eliminar la camiseta"]);
        }
    }

    //Logica de descuentos y precios finales
    public function obtenerCamisetasParaCliente($cliente_id) {
    if (empty($cliente_id)) {
        http_response_code(400);
        echo json_encode(["mensaje" => "ID de cliente no proporcionado"]);
        return;
    }

    $camisetas = $this->camiseta->obtenerCamisetasConPrecioFinal($cliente_id);

    if (!empty($camisetas)) {
        http_response_code(200);
        echo json_encode($camisetas);
    } else {
        http_response_code(404);
        echo json_encode(["mensaje" => "No se encontraron camisetas"]);
    }
}


}
?>
