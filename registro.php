<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Información de la conexión a la base de datos utilizando una URL de conexión
$db_url = "mysql://root:ayuTUoGHOnjGkPLSmxidVbDDoNQlWhuI@roundhouse.proxy.rlwy.net:49013/railway";
$url = parse_url($db_url);

$host = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$database = substr($url["path"], 1);
$port = $url["port"];

// Intentar conectar a la base de datos
$con = mysqli_connect($host, $username, $password, $database, $port);

if (!$con) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario y verificar que existen
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : '';
    $boleta = isset($_POST["boleta"]) ? $_POST["boleta"] : '';
    $grupo = isset($_POST["grupo"]) ? $_POST["grupo"] : '';
    $turno = isset($_POST["turno"]) ? $_POST["turno"] : '';
    $correo = isset($_POST["correo"]) ? $_POST["correo"] : '';
    $contrasena = isset($_POST["contrasena"]) ? $_POST["contrasena"] : '';
    $confcontrasena = isset($_POST["confcontrasena"]) ? $_POST["confcontrasena"] : '';

    // Verificar si las contraseñas coinciden
    if ($contrasena === $confcontrasena) {
        // Preparar y ejecutar la declaración SQL
        $statement = mysqli_prepare($con, "INSERT INTO Registro (nombre, boleta, grupo, turno, correo, contrasena) VALUES (?, ?, ?, ?, ?, ?)");
        if ($statement) {
            mysqli_stmt_bind_param($statement, "ssssss", $nombre, $boleta, $grupo, $turno, $correo, $contrasena);
            mysqli_stmt_execute($statement);
            
            // Preparar la respuesta
            $response = array();
            $response["success"] = true;
        } else {
            // Preparar la respuesta en caso de error
            $response = array();
            $response["success"] = false;
            $response["error"] = "Error al preparar la declaración SQL: " . mysqli_error($con);
        }
    } else {
        // Preparar la respuesta en caso de error
        $response = array();
        $response["success"] = false;
        $response["error"] = "Las contraseñas no coinciden";
    }

    // Devolver la respuesta en formato JSON
    echo json_encode($response);
}
?>

