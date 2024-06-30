<?php
include 'db_config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $isbn = $_POST['isbn'];
    $editorial = $_POST['editorial'];
    $publicacion = $_POST['publicacion'];
    $categoria = $_POST['categoria'];
    $cantidad = $_POST['cantidad'];
    $adquisicion = $_POST['adquisicion'];
    $ubicacion = $_POST['ubicacion'];

    $sql = "INSERT INTO libros (titulo, autor, isbn, editorial, publicacion, categoria, cantidad, adquisicion, ubicacion) 
            VALUES ('$titulo', '$autor', '$isbn', '$editorial', '$publicacion', '$categoria', '$cantidad', '$adquisicion', '$ubicacion')";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo libro añadido exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Nuevo Libro</title>
</head>
<body>
    <h1>Añadir Nuevo Libro</h1>
    <form method="POST" action="añadir_libros.php">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" required><br>
        
        <label for="autor">Autor:</label>
        <input type="text" name="autor" id="autor" required><br>
        
        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" id="isbn" required><br>
        
        <label for="editorial">Editorial:</label>
        <input type="text" name="editorial" id="editorial" required><br>
        
        <label for="publicacion">Fecha de Publicación:</label>
        <input type="date" name="publicacion" id="publicacion" required><br>
        
        <label for="categoria">Categoría:</label>
        <input type="text" name="categoria" id="categoria" required><br>
        
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" required><br>
        
        <label for="adquisicion">Fecha de Adquisición:</label>
        <input type="date" name="adquisicion" id="adquisicion" required><br>
        
        <label for="ubicacion">Ubicación:</label>
        <input type="text" name="ubicacion" id="ubicacion" required><br>
        
        <button type="submit">Añadir Libro</button>
    </form>
    <a href="agregar_editar_libros.php">Volver al menu</a>
</body>
</html>
