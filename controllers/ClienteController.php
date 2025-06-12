<?php
require_once './models/Cliente.php';

class ClienteController {
    private $cliente;

    public function __construct($db) {
        $this->cliente = new Cliente($db);
    }


    public function insertarCliente($data) {
        if (!empty($data['nombre_comercial']) && !empty($data['rut']) &&
            !empty($data['ciudad']) && !empty($data['region']) &&
            !empty($data['categoria']) && !empty($data['contacto_nombre']) &&
            !empty($data['contacto_correo']) && isset($data['porcentaje_descuento'])) {

            $resultado = $this->cliente->insertarCliente($data);

            if ($resultado) {
                http_response_code(201);
                echo json_encode(["mensaje" => "Cliente insertado correctamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["mensaje" => "No se pudo insertar el cliente"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "Faltan campos requeridos"]);
        }
    }

    
    public function obtenerClientes() {
        try {
            $clientes = $this->cliente->obtenerClientes();
            if (!empty($clientes)) {
                http_response_code(200);
                echo json_encode($clientes);
            } else {
                http_response_code(404);
                echo json_encode(["mensaje" => "No se encontraron clientes"]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["mensaje" => "Error al obtener clientes", "detalle" => $e->getMessage()]);
        }
    }

    
    public function obtenerClientePorId($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de cliente no proporcionado"]);
            return;
        }

        $cliente = $this->cliente->obtenerClientePorId($id);

        if ($cliente) {
            http_response_code(200);
            echo json_encode($cliente);
        } else {
            http_response_code(404);
            echo json_encode(["mensaje" => "Cliente no encontrado"]);
        }
    }

    
    public function actualizarCliente($id, $data) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de cliente no proporcionado"]);
            return;
        }

        if (!empty($data['nombre_comercial']) && !empty($data['rut']) &&
            !empty($data['ciudad']) && !empty($data['region']) &&
            !empty($data['categoria']) && !empty($data['contacto_nombre']) &&
            !empty($data['contacto_correo']) && isset($data['porcentaje_descuento'])) {

            $resultado = $this->cliente->actualizarCliente($id, $data);

            if ($resultado) {
                http_response_code(200);
                echo json_encode(["mensaje" => "Cliente actualizado correctamente"]);
            } else {
                http_response_code(500);
                echo json_encode(["mensaje" => "No se pudo actualizar el cliente"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "Faltan campos requeridos para actualización"]);
        }
    }

    
    public function actualizarParcialCliente($id, $data) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de cliente no proporcionado"]);
            return;
        }

        if (!empty($data)) {
            $resultado = $this->cliente->actualizarCliente($id, $data);

            if ($resultado) {
                http_response_code(200);
                echo json_encode(["mensaje" => "Cliente actualizado parcialmente"]);
            } else {
                http_response_code(500);
                echo json_encode(["mensaje" => "No se pudo actualizar el cliente"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["mensaje" => "No se proporcionaron datos para actualizar"]);
        }
    }

    
    public function eliminarCliente($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(["mensaje" => "ID de cliente no proporcionado"]);
            return;
        }

        $resultado = $this->cliente->eliminarCliente($id);
        if ($resultado) {
            http_response_code(200);
            echo json_encode(["mensaje" => "Cliente eliminado correctamente"]);
        } else {
            http_response_code(500);
            echo json_encode(["mensaje" => "No se pudo eliminar el cliente"]);
        }
    }
}
?>
