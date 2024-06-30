<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Alumno</title>
    <style>
        form { margin-bottom: 20px; }
        img { display: block; margin-top: 10px; }
    </style>
</head>
<body>
<?php
include 'db_config.php'; // Incluir el archivo de configuración de la base de datos

$alumno = array(); // Array para almacenar los datos del alumno

// Consulta para obtener la lista de alumnos
$sql_list = "SELECT id_alumnos, nombre, apellido FROM Alumnos";
$result_list = mysqli_query($conn, $sql_list);
if (!$result_list) {
    die("Error en la consulta: " . mysqli_error($conn));
}

// Verificar si se seleccionó un alumno del menú desplegable
if (isset($_POST['alumno']) && !isset($_POST['submit'])) {
    $id_alumnos = $_POST['alumno'];

    // Consultar los datos del alumno seleccionado
    $sql = "SELECT * FROM Alumnos WHERE id_alumnos = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_alumnos); // 'i' indica que la variable es de tipo entero

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $alumno = mysqli_fetch_assoc($result);
    } else {
        echo "No se encontró ningún alumno con ese ID.";
    }
}

// Verificar si se envió el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Procesar el formulario cuando se envíe
    $id_alumnos = $_POST['id_alumnos'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $edad = $_POST['edad'];
    $curso = $_POST['curso'];
    $padres = $_POST['padres'];

    $foto = ''; // Variable para manejar la imagen
    
    // Verificar si se subió una nueva foto
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($foto);
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
    }

    // Construir la consulta SQL para actualizar los datos del alumno
    $sql = "UPDATE Alumnos SET 
            nombre='$nombre', 
            apellido='$apellido', 
            dni='$dni', 
            edad='$edad', 
            curso='$curso', 
            padres='$padres'";

    // Agregar la ruta de la foto si se proporcionó una nueva
    if (!empty($foto)) {
        $sql .= ", foto='$target_file'";
    }

    $sql .= " WHERE id_alumnos='$id_alumnos'";

    // Ejecutar la consulta SQL
    if (mysqli_query($conn, $sql)) {
        echo "Alumno actualizado correctamente.";
        // Actualizar $alumno después de la edición
        $sql_select = "SELECT * FROM Alumnos WHERE id_alumnos = '$id_alumnos'";
        $result_select = mysqli_query($conn, $sql_select);

        if ($result_select && mysqli_num_rows($result_select) > 0) {
            $alumno = mysqli_fetch_assoc($result_select);
        }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

?>

<h1>Editar Alumno</h1>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" id="id_alumnos" name="id_alumnos" value="<?php echo isset($alumno['id_alumnos']) ? $alumno['id_alumnos'] : ''; ?>">
    
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo isset($alumno['nombre']) ? $alumno['nombre'] : ''; ?>" required><br>

    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="apellido" value="<?php echo isset($alumno['apellido']) ? $alumno['apellido'] : ''; ?>" required><br>

    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni" value="<?php echo isset($alumno['dni']) ? $alumno['dni'] : ''; ?>" required><br>

    <label for="edad">Edad:</label>
    <input type="number" id="edad" name="edad" value="<?php echo isset($alumno['edad']) ? $alumno['edad'] : ''; ?>" required><br>

    <label for="curso">Curso:</label>
    <input type="text" id="curso" name="curso" value="<?php echo isset($alumno['curso']) ? $alumno['curso'] : ''; ?>" required><br>

    <label for="padres">Padres:</label>
    <input type="text" id="padres" name="padres" value="<?php echo isset($alumno['padres']) ? $alumno['padres'] : ''; ?>" required><br>

    <label for="foto">Foto (deja en blanco si no deseas cambiarla):</label>
    <input type="file" id="foto" name="foto"><br>
    <?php if (!empty($alumno['foto'])): ?>
        <img src="<?php echo $alumno['foto']; ?>" alt="Foto Actual" style="max-width: 150px; max-height: 150px;"><br>
    <?php else: ?>
        <img src="" alt="Foto Actual" style="max-width: 150px; max-height: 150px;"><br>
    <?php endif; ?>

    <input type="submit" name="submit" value="Guardar Cambios">
</form>

<a href="dashboard.html">Volver al menú</a>

<h2>Seleccionar Alumno</h2>
<form id="selecciona_alumno" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <select name="alumno" onchange="this.form.submit()">
        <option value=''>Selecciona un alumno</option>
        <?php
        if ($result_list && mysqli_num_rows($result_list) > 0) {
            while ($row = mysqli_fetch_assoc($result_list)) {
                $selected = (isset($alumno['id_alumnos']) && $row['id_alumnos'] == $alumno['id_alumnos']) ? 'selected' : '';
                echo "<option value='" . $row['id_alumnos'] . "' $selected>" . $row['nombre'] . " " . $row['apellido'] . "</option>";
            }
        } else {
            echo "<option value=''>No hay alumnos disponibles</option>";
        }
        ?>
    </select>
</form>

</body>
</html>
