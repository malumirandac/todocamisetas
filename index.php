<?php
// index.php - API REST para camisetas y clientes

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE');
header('Content-Type: application/json');

require_once './config/database.php';
require_once './controllers/CamisetaController.php';
require_once './controllers/ClienteController.php';

// Inicializar base de datos y controladores
$database = new Database();
$db = $database->getConnection();

$camisetaController = new CamisetaController($db);
$clienteController = new ClienteController($db);

// Extraer ruta y método
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = explode('/', $path);

// Ajusta aquí si tu proyecto está en un subdirectorio (por ejemplo /todocamisetas)
$recurso = $segments[1] ?? '';  // /todocamisetas/clientes → 'clientes'
$id = $segments[2] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

// Soporte para preflight OPTIONS
if ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Función utilitaria para leer JSON
function getJsonInput() {
    $input = json_decode(file_get_contents("php://input"), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos JSON inválidos"]);
        exit();
    }
    return $input;
}

// Enrutamiento por recurso
switch ($recurso) {
    case 'camisetas':
        if ($method === 'GET' && empty($id)) {
            $camisetaController->obtenerCamisetas();
        } elseif ($method === 'GET' && $id) {
            $camisetaController->obtenerCamisetaPorId($id);
        } elseif ($method === 'POST') {
            $camisetaController->insertarCamiseta(getJsonInput());
        } elseif ($method === 'PUT') {
            $camisetaController->actualizarCamiseta($id, getJsonInput());
        } elseif ($method === 'PATCH') {
            $camisetaController->actualizarParcialCamiseta($id, getJsonInput());
        } elseif ($method === 'DELETE') {
            $camisetaController->eliminarCamiseta($id);
        } else {
            http_response_code(405);
            echo json_encode(["mensaje" => "Método no permitido"]);
        }
        break;

    case 'clientes':
        if ($method === 'GET' && empty($id)) {
            $clienteController->obtenerClientes();
        } elseif ($method === 'GET' && $id) {
            $clienteController->obtenerClientePorId($id);
        } elseif ($method === 'POST') {
            $clienteController->insertarCliente(getJsonInput());
        } elseif ($method === 'PUT') {
            $clienteController->actualizarCliente($id, getJsonInput());
        } elseif ($method === 'PATCH') {
            $clienteController->actualizarParcialCliente($id, getJsonInput());
        } elseif ($method === 'DELETE') {
            $clienteController->eliminarCliente($id);
        } else {
            http_response_code(405);
            echo json_encode(["mensaje" => "Método no permitido"]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["mensaje" => "Ruta no encontrada"]);
        break;
}

////Logica de descuentos y precios finales
    case 'camisetas-cliente':
        if ($method === 'GET' && $id) {
            $camisetaController->obtenerCamisetasParaCliente($id);
        }
        break;


?>
