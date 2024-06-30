<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Padre</title>
    <style>
        form { margin-bottom: 20px; }
    </style>
</head>
<body>
<?php
include 'db_config.php'; // Incluir el archivo de configuración de la base de datos

$padre = array(); // Array para almacenar los datos del padre

// Consulta para obtener la lista de padres
$sql_list = "SELECT id, nombre, apellido FROM Padres";
$result_list = mysqli_query($conn, $sql_list);
if (!$result_list) {
    die("Error en la consulta: " . mysqli_error($conn));
}

// Verificar si se seleccionó un padre del menú desplegable
if (isset($_POST['padre']) && !isset($_POST['submit'])) {
    $id_padre = $_POST['padre'];

    // Consultar los datos del padre seleccionado
    $sql = "SELECT * FROM Padres WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_padre); // 'i' indica que la variable es de tipo entero

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $padre = mysqli_fetch_assoc($result);
    } else {
        echo "No se encontró ningún padre con ese ID.";
    }
}

// Verificar si se envió el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Procesar el formulario cuando se envíe
    $id_padre = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $edad = $_POST['edad'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    
    // Obtener los hijos seleccionados
    $hijos = isset($_POST['hijos']) ? $_POST['hijos'] : array();
    
    // Obtener nombres completos de los hijos seleccionados
    $nombres_hijos = array();
    foreach ($hijos as $hijo_id) {
        $query_hijo = "SELECT nombre, apellido FROM Alumnos WHERE id_alumnos = $hijo_id";
        $result_hijo = $conn->query($query_hijo);
        if ($result_hijo->num_rows > 0) {
            $row_hijo = $result_hijo->fetch_assoc();
            $nombre_hijo = $row_hijo['nombre'] . " " . $row_hijo['apellido'];
            $nombres_hijos[] = $nombre_hijo;
        }
    }
    
    // Convertir nombres de hijos a cadena separada por comas
    $hijos_str = implode(", ", $nombres_hijos);

    // Construir la consulta SQL para actualizar los datos del padre
    $sql = "UPDATE Padres SET nombre='$nombre', apellido='$apellido', dni='$dni', edad='$edad', hijos='$hijos_str', telefono='$telefono', email='$email' WHERE id='$id_padre'";

    // Ejecutar la consulta SQL
    if (mysqli_query($conn, $sql)) {
        echo "Padre actualizado correctamente.";
        // Actualizar $padre después de la edición
        $sql_select = "SELECT * FROM Padres WHERE id = '$id_padre'";
        $result_select = mysqli_query($conn, $sql_select);

        if ($result_select && mysqli_num_rows($result_select) > 0) {
            $padre = mysqli_fetch_assoc($result_select);
        }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

?>

<h1>Editar Padre</h1>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
    <input type="hidden" id="id" name="id" value="<?php echo isset($padre['id']) ? $padre['id'] : ''; ?>">
    
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo isset($padre['nombre']) ? $padre['nombre'] : ''; ?>" required><br>

    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="apellido" value="<?php echo isset($padre['apellido']) ? $padre['apellido'] : ''; ?>" required><br>

    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni" value="<?php echo isset($padre['dni']) ? $padre['dni'] : ''; ?>" required><br>

    <label for="edad">Edad:</label>
    <input type="number" id="edad" name="edad" value="<?php echo isset($padre['edad']) ? $padre['edad'] : ''; ?>" required><br>

    <label for="telefono">Teléfono:</label>
    <input type="text" id="telefono" name="telefono" value="<?php echo isset($padre['telefono']) ? $padre['telefono'] : ''; ?>" required><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo isset($padre['email']) ? $padre['email'] : ''; ?>" required><br>

    <label for="hijos">Hijos:</label><br>
    <select id="hijos" name="hijos[]" multiple>
        <?php
        // Consulta para obtener la lista de alumnos (hijos)
        $query = "SELECT id_alumnos, nombre, apellido FROM Alumnos";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $selected = (strpos($padre['hijos'], $row['nombre'] . " " . $row['apellido']) !== false) ? 'selected' : '';
                echo "<option value='".$row['id_alumnos']."' $selected>".$row['nombre']." ".$row['apellido']."</option>";
            }
        }
        ?>
    </select><br>

    <input type="submit" name="submit" value="Guardar Cambios">
</form>

<a href="dashboard.html">Volver al menú</a>

<h2>Seleccionar Padre</h2>
<form id="selecciona_padre" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <select name="padre" onchange="this.form.submit()">
        <option value=''>Selecciona un padre</option>
        <?php
        if ($result_list && mysqli_num_rows($result_list) > 0) {
            while ($row = mysqli_fetch_assoc($result_list)) {
                $selected = (isset($padre['id']) && $row['id'] == $padre['id']) ? 'selected' : '';
                echo "<option value='" . $row['id'] . "' $selected>" . $row['nombre'] . " " . $row['apellido'] . "</option>";
            }
        } else {
            echo "<option value=''>No hay padres disponibles</option>";
        }
        ?>
    </select>
</form>

</body>
</html>
