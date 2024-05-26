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
    $boleta = isset($_POST["boleta"]) ? $_POST["boleta"] : '';
    $contraseña = isset($_POST["contraseña"]) ? $_POST["contraseña"] : '';

    // Preparar y ejecutar la declaración SQL
    $statement = mysqli_prepare($con, "SELECT * FROM Registro WHERE Boleta = ? AND Contrasena = ?");
    if ($statement) {
        mysqli_stmt_bind_param($statement, "ss", $boleta, $contraseña);
        mysqli_stmt_execute($statement);
        
        // Enlazar los resultados
        mysqli_stmt_store_result($statement);
        mysqli_stmt_bind_result($statement, $nombre, $boleta, $grupo, $turno, $correo, $contraseña);
        
        // Preparar la respuesta
        $response = array();
        $response["success"] = true;  
        
        // Verificar si se encontraron resultados
        if (mysqli_stmt_fetch($statement)) {
            $response["success"] = true;  
            $response["nombre"] = $nombre;
            $response["boleta"] = $boleta;
            $response["grupo"] = $grupo;
            $response["turno"] = $turno;
            $response["correo"] = $correo;
            $response["contraseña"] = $contraseña;
        }
    } else {
        // Preparar la respuesta en caso de error
        $response = array();
        $response["successos"] = false;
        $response["error"] = "Error al preparar la declaración SQL: " . mysqli_error($con);
    }

    // Devolver la respuesta en formato JSON
    echo json_encode($response);
}
?>

