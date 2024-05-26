<?php
// Cadena de conexión proporcionada por el servidor de bases de datos
$conexion_url = "mysql://root:ayuTUoGHOnjGkPLSmxidVbDDoNQlWhuI@roundhouse.proxy.rlwy.net:49013/railway";

// Parsear la cadena de conexión
$datos_conexion = parse_url($conexion_url);

// Obtener los datos de conexión
$usuario = $datos_conexion['user'];
$contrasena = $datos_conexion['pass'];
$host = $datos_conexion['host'];
$puerto = $datos_conexion['port'];
$basedatos = ltrim($datos_conexion['path'], '/');

// Crear conexión
$conn = new mysqli($host, $usuario, $contrasena, $basedatos, $puerto);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}


// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $fisica = $_POST['fisica'];
    $idFormula = $_POST['id_formula'];
    $nombreFormula = $_POST['nombre_formula'];
    $descripcion = $_POST['descripcion'];
    $tablaSeleccionada = $_POST['tabla'];

    // Subir imagen
    $imagenNombre = $_FILES['imagen']['name'];
    $imagenTipo = $_FILES['imagen']['type'];
    $imagenTemp = $_FILES['imagen']['tmp_name'];
    $imagenTamaño = $_FILES['imagen']['size'];
    
    // Comprobación de tipo de imagen
    $permitidos = array("image/jpg", "image/jpeg", "image/png");
    if (in_array($imagenTipo, $permitidos)) {
        $ruta = "uploads/" . $imagenNombre;
        move_uploaded_file($imagenTemp, $ruta);
    } else {
        echo "Formato de imagen no válido. Sube una imagen en formato JPG, JPEG o PNG.";
        exit;
    }

    // Insertar los datos en la tabla seleccionada
    $sql = "INSERT INTO $tablaSeleccionada (Fisica, Id_Formula, NomForm, IMGForm, Descripcion) VALUES ('$fisica', '$idFormula', '$nombreFormula', '$ruta', '$descripcion')";

    if ($conn->query($sql) === TRUE) {
        echo "Los datos se han insertado correctamente en la tabla $tablaSeleccionada.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>