<?php
require_once './models/Talla.php';

class TallaController {
    private $talla;

    public function __construct($db) {
        $this->talla = new Talla($db);
    }

    public function obtenerTallas() {
        $tallas = $this->talla->obtenerTallas();
        echo json_encode($tallas);
    }

    public function insertarTalla($data) {
        if (isset($data['talla'])) {
            $resultado = $this->talla->insertarTalla($data);
            if ($resultado) {
                http_response_code(201);
                echo json_encode(["mensaje" => "Talla creada"]);
            } else {
                http_response_code(500);
                echo json_encode(["mensaje" => "Error al crear talla"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "Datos inválidos"]);
        }
    }

    public function actualizarTalla($id, $data) {
        if (isset($data['talla'])) {
            $resultado = $this->talla->actualizarTalla($id, $data);
            if ($resultado) {
                echo json_encode(["mensaje" => "Talla actualizada"]);
            } else {
                http_response_code(500);
                echo json_encode(["mensaje" => "Error al actualizar talla"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "Datos inválidos"]);
        }
    }

    public function eliminarTalla($id) {
        $resultado = $this->talla->eliminarTalla($id);
        if ($resultado) {
            echo json_encode(["mensaje" => "Talla eliminada"]);
        } else {
            http_response_code(500);
            echo json_encode(["mensaje" => "Error al eliminar talla"]);
        }
    }
}
?>
