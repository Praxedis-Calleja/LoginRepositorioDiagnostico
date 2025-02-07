<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Acceso denegado: se requiere método POST.');
}

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "login";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// Verificar que se hayan recibido los datos necesarios
if (!isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['query_type'], $_POST['message'])) {
    die("Error: faltan datos en el formulario.");
}

// Validar que los campos no estén vacíos
if (
    empty(trim($_POST['first_name'])) ||
    empty(trim($_POST['last_name'])) ||
    empty(trim($_POST['email'])) ||
    empty(trim($_POST['query_type'])) ||
    empty(trim($_POST['message']))
) {
    die("Error: Uno o más campos obligatorios están vacíos.");
}

$first_name = $conn->real_escape_string(trim($_POST['first_name']));
$last_name  = $conn->real_escape_string(trim($_POST['last_name']));
$email      = $conn->real_escape_string(trim($_POST['email']));
$query_type = $conn->real_escape_string(trim($_POST['query_type']));
$message    = $conn->real_escape_string(trim($_POST['message']));
$consent    = isset($_POST['consent']) ? 1 : 0;

$sql = "INSERT INTO loginusuarios (first_name, last_name, email, query_type, message, consent)
        VALUES ('$first_name', '$last_name', '$email', '$query_type', '$message', $consent)";

if ($conn->query($sql) === TRUE) {
    if ($conn->affected_rows > 0) {
        error_log("Inserción exitosa. Filas afectadas: " . $conn->affected_rows);
    } else {
        error_log("La consulta se ejecutó, pero no se afectaron filas.");
    }
    header("Location: ../index.html?success=true");
    exit();
} else {
    error_log("Error al insertar: " . $conn->error);
    die("Error al insertar en la base de datos: " . $conn->error);
}

$conn->close();
?>