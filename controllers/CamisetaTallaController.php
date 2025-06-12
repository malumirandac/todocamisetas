<?php
require_once './models/CamisetaTalla.php';

class CamisetaTallaController {
    private $modelo;

    public function __construct($db) {
        $this->modelo = new CamisetaTalla($db);
    }

    public function insertar($data) {
        if (!empty($data['camiseta_id']) && !empty($data['talla_id']) && isset($data['cantidad'])) {
            $resultado = $this->modelo->insertar($data);
            if ($resultado) {
                http_response_code(201);
                echo json_encode(["mensaje" => "Asignación creada correctamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["mensaje" => "Error al insertar"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "Datos incompletos"]);
        }
    }

    public function obtenerTodos() {
        $resultados = $this->modelo->obtenerTodos();
        echo json_encode($resultados);
    }

    public function actualizar($id, $data) {
        if (!empty($id) && isset($data['cantidad'])) {
            $resultado = $this->modelo->actualizar($id, $data);
            if ($resultado) {
                echo json_encode(["mensaje" => "Actualizado correctamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["mensaje" => "Error al actualizar"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "Datos incompletos"]);
        }
    }

    public function eliminar($id) {
        if (!empty($id)) {
            $resultado = $this->modelo->eliminar($id);
            if ($resultado) {
                echo json_encode(["mensaje" => "Eliminado correctamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["mensaje" => "Error al eliminar"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID no proporcionado"]);
        }
    }
}
?>
