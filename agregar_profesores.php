<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>agregar Profesor</title>
</head>
<body>
<h1>agregar Profesor</h1>

<form action="agregar_profesores.php" method="POST" enctype="multipart/form-data">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required><br>

    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="apellido" required><br>

    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni" required><br>

    <label for="edad">Edad:</label>
    <input type="number" id="edad" name="edad" required><br>

    <label for="telefono">Teléfono:</label>
    <input type="text" id="telefono" name="telefono" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br>

    <label for="materias">Materias:</label>
    <input type="text" id="materias" name="materias" required><br>

    <label for="cursos">Cursos:</label>
    <input type="text" id="cursos" name="cursos" required><br>

    <label for="foto">Foto:</label>
    <input type="file" id="foto" name="foto"><br>

    <input type="submit" name="submit" value="agregar Profesor">
</form>

<a href="profesores.php">Volver al menú anterior</a>

<?php
if (isset($_POST['submit'])) {
    include 'db_config.php';

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $edad = $_POST['edad'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $materias = $_POST['materias'];
    $cursos = $_POST['cursos'];
    
    // Manejo del archivo de foto
    $foto = $_FILES['foto']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($foto);
    move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);

    // Inserción en la base de datos
    $sql = "INSERT INTO Profesores (nombre, apellido, dni, edad, telefono, email, materias, cursos, foto) 
            VALUES ('$nombre', '$apellido', '$dni', '$edad', '$telefono', '$email', '$materias', '$cursos', '$target_file')";

    if (mysqli_query($conn, $sql)) {
        echo "Profesor añadido correctamente.";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
</body>
</html>
