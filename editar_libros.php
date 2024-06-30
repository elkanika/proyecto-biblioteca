<?php
include 'db_config.php';

// Proceso de actualización de libro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $autor = mysqli_real_escape_string($conn, $_POST['autor']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $editorial = mysqli_real_escape_string($conn, $_POST['editorial']);
    $publicacion = mysqli_real_escape_string($conn, $_POST['publicacion']);
    $categoria = mysqli_real_escape_string($conn, $_POST['categoria']);
    $cantidad = mysqli_real_escape_string($conn, $_POST['cantidad']);
    $adquisicion = mysqli_real_escape_string($conn, $_POST['adquisicion']);
    $ubicacion = mysqli_real_escape_string($conn, $_POST['ubicacion']);

    $sql = "UPDATE libros SET titulo=?, autor=?, isbn=?, editorial=?, publicacion=?, categoria=?, cantidad=?, adquisicion=?, ubicacion=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssissi", $titulo, $autor, $isbn, $editorial, $publicacion, $categoria, $cantidad, $adquisicion, $ubicacion, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: editar_libros.php");
    exit();
}

// Obtener datos para mostrar en la tabla
$query = "SELECT * FROM libros";
$libros = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Libros</title>
</head>
<body>
<h1>Editar Libros</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Autor</th>
        <th>ISBN</th>
        <th>Editorial</th>
        <th>Fecha de Publicación</th>
        <th>Categoría</th>
        <th>Cantidad</th>
        <th>Fecha de Adquisición</th>
        <th>Ubicación</th>
        <th>Acciones</th>
    </tr>
    <?php while($libro = $libros->fetch_assoc()): ?>
    <tr>
        <td><?php echo $libro['id']; ?></td>
        <td><?php echo $libro['titulo']; ?></td>
        <td><?php echo $libro['autor']; ?></td>
        <td><?php echo $libro['isbn']; ?></td>
        <td><?php echo $libro['editorial']; ?></td>
        <td><?php echo $libro['publicacion']; ?></td>
        <td><?php echo $libro['categoria']; ?></td>
        <td><?php echo $libro['cantidad']; ?></td>
        <td><?php echo $libro['adquisicion']; ?></td>
        <td><?php echo $libro['ubicacion']; ?></td>
        <td>
            <a href="editar_libros.php?edit=<?php echo $libro['id']; ?>">Editar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php if (isset($_GET['edit'])): 
    $id = $_GET['edit'];
    $libro = $conn->query("SELECT * FROM libros WHERE id=$id")->fetch_assoc();
?>
<h1>Editar Libro</h1>
<form method="POST" action="editar_libros.php">
    <input type="hidden" name="id" value="<?php echo $libro['id']; ?>">
    
    <label for="titulo">Título:</label>
    <input type="text" name="titulo" id="titulo" value="<?php echo $libro['titulo']; ?>" required><br>
    
    <label for="autor">Autor:</label>
    <input type="text" name="autor" id="autor" value="<?php echo $libro['autor']; ?>" required><br>
    
    <label for="isbn">ISBN:</label>
    <input type="text" name="isbn" id="isbn" value="<?php echo $libro['isbn']; ?>" required><br>
    
    <label for="editorial">Editorial:</label>
    <input type="text" name="editorial" id="editorial" value="<?php echo $libro['editorial']; ?>" required><br>
    
    <label for="publicacion">Fecha de Publicación:</label>
    <input type="date" name="publicacion" id="publicacion" value="<?php echo $libro['publicacion']; ?>" required><br>
    
    <label for="categoria">Categoría:</label>
    <input type="text" name="categoria" id="categoria" value="<?php echo $libro['categoria']; ?>" required><br>
    
    <label for="cantidad">Cantidad:</label>
    <input type="number" name="cantidad" id="cantidad" value="<?php echo $libro['cantidad']; ?>" required><br>
    
    <label for="adquisicion">Fecha de Adquisición:</label>
    <input type="date" name="adquisicion" id="adquisicion" value="<?php echo $libro['adquisicion']; ?>" required><br>
    
    <label for="ubicacion">Ubicación:</label>
    <input type="text" name="ubicacion" id="ubicacion" value="<?php echo $libro['ubicacion']; ?>" required><br>
    
    <button type="submit">Guardar Cambios</button>
</form>
<?php endif; ?>

<a href="agregar_editar_libros.php">Volver al menú</a>

</body>
</html>
