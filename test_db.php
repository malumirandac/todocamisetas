<?php
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "<p>✅ Conexión a la base de datos exitosa.</p>";
} else {
    echo "<p>❌ No se pudo conectar a la base de datos.</p>";
}
?>
