<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Padre</title>
    <style>
        /* Estilo opcional para mejorar la apariencia del formulario */
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h2>Agregar Nuevo Padre</h2>

    <?php
    // Incluir el archivo de configuración de la base de datos
    include 'db_config.php';

    // Procesar el formulario cuando se envíe
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Obtener los datos del formulario
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

        // Preparar consulta para insertar el padre
        $insert_padre = "INSERT INTO Padres (nombre, apellido, dni, edad, hijos, telefono, email) 
                         VALUES ('$nombre', '$apellido', '$dni', $edad, '$hijos_str', '$telefono', '$email')";

        if ($conn->query($insert_padre) === TRUE) {
            echo "<p>Padre agregado correctamente.</p>";
        } else {
            echo "<p>Error al agregar padre: " . $conn->error . "</p>";
        }
    }
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        
        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
        </div>
        
        <div class="form-group">
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni" required>
        </div>
        
        <div class="form-group">
            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" required>
        </div>
        
        <div class="form-group">
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="hijos">Hijos:</label><br>
            <select id="hijos" name="hijos[]" multiple>
                <?php
                // Consulta para obtener la lista de alumnos (hijos)
                $query = "SELECT id_alumnos, nombre, apellido FROM Alumnos";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['id_alumnos']."'>".$row['nombre']." ".$row['apellido']."</option>";
                    }
                }
                ?>
            </select>
        </div>
        
        <input type="submit" value="Guardar Padre">
    </form>

    <?php
    // Cerrar conexión
    $conn->close();
    ?>
    <a href="dashboard.html">Volver al menú</a>
</body>
</html>
