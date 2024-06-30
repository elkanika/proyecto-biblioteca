<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Profesor</title>
    <style>
        form { margin-bottom: 20px; }
        img { display: block; margin-top: 10px; }
    </style>
</head>
<body>
<?php
include 'db_config.php'; // Incluir el archivo de configuración de la base de datos

$profesor = array(); // Array para almacenar los datos del profesor

// Consulta para obtener la lista de profesores
$sql_list = "SELECT id_profesores, nombre, apellido FROM Profesores";
$result_list = mysqli_query($conn, $sql_list);
if (!$result_list) {
    die("Error en la consulta: " . mysqli_error($conn));
}


// Verificar si se seleccionó un profesor del menú desplegable
if (isset($_POST['profesor']) && !isset($_POST['submit'])) {
    $id_profesores = $_POST['profesor'];

    // Consultar los datos del profesor seleccionado
    $sql = "SELECT * FROM Profesores WHERE id_profesores = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_profesores); // 'i' indica que la variable es de tipo entero

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $profesor = mysqli_fetch_assoc($result);
    } else {
        echo "No se encontró ningún profesor con ese ID.";
    }
}


// Verificar si se envió el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Procesar el formulario cuando se envíe
    $id_profesores = $_POST['id_profesores'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $edad = $_POST['edad'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $materias = $_POST['materias'];
    $cursos = $_POST['cursos'];

    $foto = ''; // Variable para manejar la imagen
    
    // Verificar si se subió una nueva foto
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($foto);
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
    }

    // Construir la consulta SQL para actualizar los datos del profesor
    $sql = "UPDATE Profesores SET nombre='$nombre', apellido='$apellido', dni='$dni', edad='$edad', telefono='$telefono', email='$email', materias='$materias', cursos='$cursos'";

    // Agregar la ruta de la foto si se proporcionó una nueva
    if (!empty($foto)) {
        $sql .= ", foto='$target_file'";
    }

    $sql .= " WHERE id_profesores='$id_profesores'";

    // Ejecutar la consulta SQL
    if (mysqli_query($conn, $sql)) {
        echo "Profesor actualizado correctamente.";
        // Actualizar $profesor después de la edición
        $sql_select = "SELECT * FROM Profesores WHERE id_profesores = '$id_profesores'";
        $result_select = mysqli_query($conn, $sql_select);

        if ($result_select && mysqli_num_rows($result_select) > 0) {
            $profesor = mysqli_fetch_assoc($result_select);
        }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

?>

<h1>Editar Profesor</h1>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" id="id_profesores" name="id_profesores" value="<?php echo isset($profesor['id_profesores']) ? $profesor['id_profesores'] : ''; ?>">
    
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo isset($profesor['nombre']) ? $profesor['nombre'] : ''; ?>" required><br>

    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="apellido" value="<?php echo isset($profesor['apellido']) ? $profesor['apellido'] : ''; ?>" required><br>

    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni" value="<?php echo isset($profesor['dni']) ? $profesor['dni'] : ''; ?>" required><br>

    <label for="edad">Edad:</label>
    <input type="number" id="edad" name="edad" value="<?php echo isset($profesor['edad']) ? $profesor['edad'] : ''; ?>" required><br>

    <label for="telefono">Teléfono:</label>
    <input type="text" id="telefono" name="telefono" value="<?php echo isset($profesor['telefono']) ? $profesor['telefono'] : ''; ?>" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo isset($profesor['email']) ? $profesor['email'] : ''; ?>" required><br>

    <label for="materias">Materias:</label>
    <input type="text" id="materias" name="materias" value="<?php echo isset($profesor['materias']) ? $profesor['materias'] : ''; ?>" required><br>

    <label for="cursos">Cursos:</label>
    <input type="text" id="cursos" name="cursos" value="<?php echo isset($profesor['cursos']) ? $profesor['cursos'] : ''; ?>" required><br>

    <label for="foto">Foto (deja en blanco si no deseas cambiarla):</label>
    <input type="file" id="foto" name="foto"><br>
    <?php if (!empty($profesor['foto'])): ?>
        <img src="<?php echo $profesor['foto']; ?>" alt="Foto Actual" style="max-width: 150px; max-height: 150px;"><br>
    <?php else: ?>
        <img src="" alt="Foto Actual" style="max-width: 150px; max-height: 150px;"><br>
    <?php endif; ?>

    <input type="submit" name="submit" value="Guardar Cambios">
</form>

<a href="dashboard.html">Volver al menú</a>

<h2>Seleccionar Profesor</h2>
<form id="selecciona_profesor" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <select name="profesor" onchange="this.form.submit()">
        <option value=''>Selecciona un profesor</option>
        <?php
        if ($result_list && mysqli_num_rows($result_list) > 0) {
            while ($row = mysqli_fetch_assoc($result_list)) {
                $selected = (isset($profesor['id_profesores']) && $row['id_profesores'] == $profesor['id_profesores']) ? 'selected' : '';
                echo "<option value='" . $row['id_profesores'] . "' $selected>" . $row['nombre'] . " " . $row['apellido'] . "</option>";
            }
        } else {
            echo "<option value=''>No hay profesores disponibles</option>";
        }
        ?>
    </select>
</form>

</body>
</html>
