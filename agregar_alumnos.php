<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Alumno</title>
    <style>
        /* Estilo opcional para mejorar la apariencia del formulario */
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <h2>Agregar Nuevo Alumno</h2>

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
        $curso = $_POST['curso'];
        $padre_id = $_POST['padre']; // ID del padre seleccionado

        // Consulta SQL para obtener el nombre completo del padre según su ID
        $query = "SELECT nombre, apellido FROM Padres WHERE id = $padre_id";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $padre_nombre = $row['nombre'] . ' ' . $row['apellido'];

            // Preparar consulta para insertar el alumno
            $insert_alumno = "INSERT INTO Alumnos (nombre, apellido, dni, edad, curso, padres) 
                              VALUES ('$nombre', '$apellido', '$dni', $edad, '$curso', '$padre_nombre')";

            if ($conn->query($insert_alumno) === TRUE) {
                echo "<p>Alumno agregado correctamente.</p>";
            } else {
                echo "<p>Error al agregar alumno: " . $conn->error . "</p>";
            }
        } else {
            echo "<p>Error: No se encontró al padre seleccionado.</p>";
        }
    }
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
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
            <label for="curso">Curso:</label>
            <input type="text" id="curso" name="curso" required>
        </div>
        
        <div class="form-group">
            <label for="padre">Padre:</label>
            <select id="padre" name="padre" required>
                <?php
                // Consulta para obtener los padres
                $query = "SELECT id, nombre, apellido FROM Padres";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['id']."'>".$row['nombre']." ".$row['apellido']."</option>";
                    }
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="foto">Foto:</label>
            <input type="file" id="foto" name="foto">
        </div>
        
        <input type="submit" value="Guardar Alumno">
    </form>

    <?php
    // Cerrar conexión
    $conn->close();
    ?>
    <a href="alumnos.php">Volver al menú</a>

    <script>
        // Función para filtrar opciones del select según el valor de búsqueda
        function filtrarPadres() {
            var input, filter, select, option, txtValue;
            input = document.getElementById('buscar_padre');
            filter = input.value.toUpperCase();
            select = document.getElementById('padre');
            options = select.getElementsByTagName('option');

            for (var i = 0; i < options.length; i++) {
                txtValue = options[i].textContent || options[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    options[i].style.display = "";
                } else {
                    options[i].style.display = "none";
                }
            }
        }

        // Evento para detectar cambios en el campo de búsqueda
        document.getElementById('buscar_padre').addEventListener('input', filtrarPadres);
    </script>
</body>
</html>
