<?php
// Incluir archivo de configuración de la base de datos
include 'db_config.php';

// Función para limpiar datos de entrada
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Verificar si se recibió el formulario por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Limpiar y obtener datos del formulario
    $usuario = clean_input($_POST["usuario"]);
    $libro = clean_input($_POST["libro"]);

    // Validar el formato del usuario (debe ser algo como A1, P2, etc.)
    if (!preg_match("/^[A-Za-z][0-9]$/", $usuario)) {
        die("Error: El formato del usuario debe ser una letra seguida de un número (por ejemplo, A1, P2, etc.)");
    }

    // Aquí puedes continuar con la inserción en la base de datos
    // Preparar la consulta SQL para insertar el préstamo
    $query = "INSERT INTO prestamos (usuario, libro_prestado) VALUES ('$usuario', '$libro')";

    // Ejecutar la consulta
    if (mysqli_query($conn, $query)) {
        echo "Préstamo registrado correctamente.";
    } else {
        echo "Error al registrar el préstamo: " . mysqli_error($conn);
    }

    // Cerrar la conexión
    mysqli_close($conn);
}
