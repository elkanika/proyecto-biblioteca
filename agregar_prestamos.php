<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Préstamo</title>
    <!-- Incluir CSS de Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
    <!-- Incluir jQuery y JS de Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
</head>
<body>
<h1 style="color: rgb(48, 118, 248);">Crear Préstamo</h1>

<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $fecha_prestamo = mysqli_real_escape_string($conn, $_POST['fecha_prestamo']);
    $fecha_devolucion = mysqli_real_escape_string($conn, $_POST['fecha_devolucion']);
    $libro_prestado = mysqli_real_escape_string($conn, $_POST['libro_prestado']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);
    $fecha_multa = mysqli_real_escape_string($conn, $_POST['fecha_multa']);
    $motivo_multa = mysqli_real_escape_string($conn, $_POST['motivo_multa']);
    $monto_multa = mysqli_real_escape_string($conn, $_POST['monto_multa']);

    $sql = "INSERT INTO prestamos (usuario, fecha_prestamo, fecha_devolucion, libro_prestado, estado, fecha_multa, motivo_multa, monto_multa)
    VALUES ('$usuario', '$fecha_prestamo', '$fecha_devolucion', '$libro_prestado', '$estado', '$fecha_multa', '$motivo_multa', '$monto_multa')";


    if (mysqli_query($conn, $sql)) {
        echo "<p>Préstamo creado con éxito.</p>";
    } else {
        echo "<p>Error: " . mysqli_error($conn) . "</p>";
    }
}
?>

<form action="agregar_prestamos.php" method="post">
    <label for="usuario">Usuario:</label>
    <select name="usuario" id="usuario" required style="width: 100%;">
        <option value="">Seleccione un usuario</option>
        <?php
        // Consulta para obtener los alumnos
        $query_alumnos = "SELECT dni, nombre, apellido FROM Alumnos";
        $result_alumnos = mysqli_query($conn, $query_alumnos);
        while ($row = mysqli_fetch_assoc($result_alumnos)) {
            echo "<option value='{$row['dni']}'>Alumno: {$row['nombre']} {$row['apellido']} (DNI: {$row['dni']})</option>";
        }

        // Consulta para obtener los profesores
        $query_profesores = "SELECT dni, nombre, apellido FROM Profesores";
        $result_profesores = mysqli_query($conn, $query_profesores);
        while ($row = mysqli_fetch_assoc($result_profesores)) {
            echo "<option value='{$row['dni']}'>Profesor: {$row['nombre']} {$row['apellido']} (DNI: {$row['dni']})</option>";
        }
        ?>
    </select><br>

    <label for="fecha_prestamo">Fecha de Préstamo:</label>
    <input type="date" name="fecha_prestamo" id="fecha_prestamo" required><br>

    <label for="fecha_devolucion">Fecha de Devolución:</label>
    <input type="date" name="fecha_devolucion" id="fecha_devolucion" required><br>

    <label for="libro_prestado">Libro Prestado:</label>
    <select name="libro_prestado" id="libro_prestado" required style="width: 100%;">
        <option value="">Seleccione un libro</option>
        <?php
        $query_libros = "SELECT id, titulo, autor FROM libros";
        $result_libros = mysqli_query($conn, $query_libros);
        while ($row = mysqli_fetch_assoc($result_libros)) {
            echo "<option value='{$row['titulo']}'>{$row['titulo']} - {$row['autor']}</option>";
        }
        ?>
    </select><br>

    <label for="estado">Estado:</label>
    <select name="estado" id="estado">
        <option value="pendiente">Pendiente</option>
        <option value="devuelto">Devuelto</option>
        <option value="vencido">Vencido</option>
    </select><br>

    <label for="fecha_multa">Fecha de Multa:</label>
    <input type="date" name="fecha_multa" id="fecha_multa"><br>

    <label for="motivo_multa">Motivo de la Multa:</label>
    <input type="text" name="motivo_multa" id="motivo_multa"><br>

    <label for="monto_multa">Monto de la Multa:</label>
    <input type="number" name="monto_multa" id="monto_multa" step="0.01"><br>

    <button type="submit">Crear Préstamo</button>
</form>

<a href="agregar_editar_prestamos.php">Volver al menú</a>

<script>
$(document).ready(function() {
    $('#usuario').select2({
        placeholder: "Seleccione un usuario",
        allowClear: true
    });
    $('#libro_prestado').select2({
        placeholder: "Seleccione un libro",
        allowClear: true
    });
});
</script>

</body>
</html>
