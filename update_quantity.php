<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $change = intval($_POST['change']);

    // Obtener la cantidad actual
    $consulta = mysqli_query($conn, "SELECT cantidad FROM libros WHERE id = $id") or die("Fallo en la consulta");
    $resultado = mysqli_fetch_array($consulta);
    $current_quantity = $resultado['cantidad'];

    // Verificar que la nueva cantidad no sea menor a 0
    if ($current_quantity + $change >= 0) {
        $instruccion = "UPDATE libros SET cantidad = cantidad + $change WHERE id = $id";
        mysqli_query($conn, $instruccion) or die("Fallo en la actualización");

        // Obtener la nueva cantidad
        $consulta = mysqli_query($conn, "SELECT cantidad FROM libros WHERE id = $id") or die("Fallo en la consulta");
        $resultado = mysqli_fetch_array($consulta);

        echo $resultado['cantidad'];
    } else {
        echo $current_quantity; // Si la cantidad sería menor a 0, no hacer nada y devolver la cantidad actual
    }
}
?>
