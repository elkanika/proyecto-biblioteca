<?php
include 'db_config.php';

// Proceso de actualización de préstamo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $fecha_prestamo = mysqli_real_escape_string($conn, $_POST['fecha_prestamo']);
    $fecha_devolucion = mysqli_real_escape_string($conn, $_POST['fecha_devolucion']);
    $libro_prestado = mysqli_real_escape_string($conn, $_POST['libro_prestado']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);
    $fecha_multa = mysqli_real_escape_string($conn, $_POST['fecha_multa']);
    $motivo_multa = mysqli_real_escape_string($conn, $_POST['motivo_multa']);
    $monto_multa = mysqli_real_escape_string($conn, $_POST['monto_multa']);

    $sql = "UPDATE prestamos SET usuario=?, fecha_prestamo=?, fecha_devolucion=?, libro_prestado=?, estado=?, fecha_multa=?, motivo_multa=?, monto_multa=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $usuario, $fecha_prestamo, $fecha_devolucion, $libro_prestado, $estado, $fecha_multa, $motivo_multa, $monto_multa, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: editar_prestamos.php");
    exit();
}

// Obtener datos para mostrar en la tabla
$query = "SELECT prestamos.id, prestamos.usuario, prestamos.fecha_prestamo, prestamos.fecha_devolucion, libros.titulo AS libro_prestado, prestamos.estado, prestamos.fecha_multa, prestamos.motivo_multa, prestamos.monto_multa
          FROM prestamos
          JOIN libros ON prestamos.libro_prestado = libros.id";
$prestamos = $conn->query($query);

// Obtener usuarios
$usuarios_query = "SELECT dni, nombre, apellido FROM Alumnos UNION SELECT dni, nombre, apellido FROM Profesores";
$usuarios_result = $conn->query($usuarios_query);

// Obtener libros
$libros_query = "SELECT id, titulo, autor FROM libros";
$libros_result = $conn->query($libros_query);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Préstamos</title>
    <!-- Incluir CSS de Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
    <!-- Incluir jQuery y JS de Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
    <style>
        select {
            width: 100%;
        }
    </style>
</head>
<body>
<h1 style="color: rgb(48, 118, 248);">Editar Préstamos</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Fecha de Préstamo</th>
        <th>Fecha de Devolución</th>
        <th>Libro Prestado</th>
        <th>Estado</th>
        <th>Fecha de Multa</th>
        <th>Motivo de Multa</th>
        <th>Monto de Multa</th>
        <th>Acciones</th>
    </tr>
    <?php while($prestamo = $prestamos->fetch_assoc()): ?>
    <tr>
        <td><?php echo $prestamo['id']; ?></td>
        <td><?php echo $prestamo['usuario']; ?></td>
        <td><?php echo $prestamo['fecha_prestamo']; ?></td>
        <td><?php echo $prestamo['fecha_devolucion']; ?></td>
        <td><?php echo $prestamo['libro_prestado']; ?></td>
        <td><?php echo $prestamo['estado']; ?></td>
        <td><?php echo $prestamo['fecha_multa']; ?></td>
        <td><?php echo $prestamo['motivo_multa']; ?></td>
        <td><?php echo $prestamo['monto_multa']; ?></td>
        <td>
            <a href="editar_prestamos.php?edit=<?php echo $prestamo['id']; ?>">Editar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php if (isset($_GET['edit'])): 
    $id = $_GET['edit'];
    $prestamo = $conn->query("SELECT * FROM prestamos WHERE id=$id")->fetch_assoc();
    
    // Obtener usuarios y libros de nuevo para el formulario de edición
    $usuarios_result = $conn->query($usuarios_query);
    $libros_result = $conn->query($libros_query);
?>
<h1 style="color: rgb(48, 118, 248);">Editar Préstamo</h1>
<form method="POST" action="editar_prestamos.php">
    <input type="hidden" name="id" value="<?php echo $prestamo['id']; ?>">
    
    <label for="usuario">Usuario:</label>
    <select name="usuario" id="usuario" required>
        <option value="">Seleccione un usuario</option>
        <?php while ($usuario = $usuarios_result->fetch_assoc()): ?>
        <option value="<?php echo $usuario['dni']; ?>" <?php if ($usuario['dni'] == $prestamo['usuario']) echo 'selected'; ?>>
            <?php echo $usuario['dni'] . ' - ' . $usuario['nombre'] . ' ' . $usuario['apellido']; ?>
        </option>
        <?php endwhile; ?>
    </select><br>
    
    <label for="fecha_prestamo">Fecha de Préstamo:</label>
    <input type="date" name="fecha_prestamo" id="fecha_prestamo" value="<?php echo $prestamo['fecha_prestamo']; ?>" required><br>
    
    <label for="fecha_devolucion">Fecha de Devolución:</label>
    <input type="date" name="fecha_devolucion" id="fecha_devolucion" value="<?php echo $prestamo['fecha_devolucion']; ?>" required><br>
    
    <label for="libro_prestado">Libro Prestado:</label>
    <select name="libro_prestado" id="libro_prestado" required>
        <option value="">Seleccione un libro</option>
        <?php while ($libro = $libros_result->fetch_assoc()): ?>
        <option value="<?php echo $libro['id']; ?>" <?php if ($libro['id'] == $prestamo['libro_prestado']) echo 'selected'; ?>>
            <?php echo $libro['titulo'] . ' - ' . $libro['autor']; ?>
        </option>
        <?php endwhile; ?>
    </select><br>
    
    <label for="estado">Estado:</label>
    <select name="estado" id="estado">
        <option value="pendiente" <?php if ($prestamo['estado'] == 'pendiente') echo 'selected'; ?>>Pendiente</option>
        <option value="devuelto" <?php if ($prestamo['estado'] == 'devuelto') echo 'selected'; ?>>Devuelto</option>
        <option value="vencido" <?php if ($prestamo['estado'] == 'vencido') echo 'selected'; ?>>Vencido</option>
    </select><br>
    
    <label for="fecha_multa">Fecha de Multa:</label>
    <input type="date" name="fecha_multa" id="fecha_multa" value="<?php echo $prestamo['fecha_multa']; ?>"><br>
    
    <label for="motivo_multa">Motivo de la Multa:</label>
    <input type="text" name="motivo_multa" id="motivo_multa" value="<?php echo $prestamo['motivo_multa']; ?>"><br>
    
    <label for="monto_multa">Monto de la Multa:</label>
    <input type="number" name="monto_multa" id="monto_multa" value="<?php echo $prestamo['monto_multa']; ?>" step="0.01"><br>
    
    <button type="submit">Guardar</button>
</form>
<?php endif; ?>

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
