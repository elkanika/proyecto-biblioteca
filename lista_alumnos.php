<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos</title>
    <script>
        function actualizaPagina() {
            var i = document.forms.selecciona.dni.selectedIndex;
            var dni = document.forms.selecciona.dni.options[i].value;
            if (dni === 'Todos') {
                window.location = 'lista_alumnos.php';
            } else {
                window.location = 'lista_alumnos.php?dni=' + dni;
            }
        }
    </script>
</head>
<body>
<h1 style="color: rgb(48, 118, 248);">Consulta de alumnos</h1>

<?php 
include 'db_config.php'; 

echo "<table class='tabla-lista'>";

echo ("<FORM NAME='selecciona' ACTION='lista_alumnos.php' METHOD='POST'>\n");
echo ("<P>Mostrar alumnos con el DNI:\n");
echo ("<SELECT NAME='dni' ONCHANGE='actualizaPagina()'>\n");

// Añadir la opción "Todos"
echo "<option value='Todos'>Todos</option>\n";

// Consultar DNIs de alumnos
$instruccion = "SELECT DISTINCT dni FROM Alumnos";
$consulta = mysqli_query($conn, $instruccion) or die("Fallo en la consulta: " . mysqli_error($conn));

$dni = isset($_REQUEST['dni']) ? $_REQUEST['dni'] : 'Todos';

while ($row = mysqli_fetch_array($consulta)) {
    $dni_db = $row['dni'];
    if ($dni_db == $dni) {
        echo ("<OPTION VALUE='$dni_db' SELECTED>$dni_db\n");
    } else {
        echo ("<OPTION VALUE='$dni_db'>$dni_db\n");
    }
}

echo ("</SELECT></P>\n");
echo ("</FORM>\n");

// Construir la consulta a la base de datos
$instruccion = "SELECT * FROM Alumnos";
if ($dni && $dni != "Todos") {
    $instruccion .= " WHERE dni ='$dni'";
}
$instruccion .= " ORDER BY id_alumnos ASC";

// Realizar la consulta a la base de datos
$consulta = mysqli_query($conn, $instruccion) or die("Fallo en la consulta: " . mysqli_error($conn));

$nfilas = mysqli_num_rows($consulta);
if ($nfilas > 0) {
    echo ("<TR>\n");
    echo ("<TH>Nombre</TH>\n");
    echo ("<TH>Apellido</TH>\n");
    echo ("<TH>DNI</TH>\n");
    echo ("<TH>Edad</TH>\n");
    echo ("<TH>Curso</TH>\n");
    echo ("<TH>Padres</TH>\n");
    echo ("<TH>Foto</TH>\n");
    echo "<th>Eliminar</th>\n";
    echo ("</TR>\n");

    while ($resultado = mysqli_fetch_array($consulta)) {
        echo ("<TR>\n");
        echo ("<TD>" . htmlspecialchars($resultado['nombre']) . "</TD>\n");
        echo ("<TD>" . htmlspecialchars($resultado['apellido']) . "</TD>\n");
        echo ("<TD>" . htmlspecialchars($resultado['dni']) . "</TD>\n");
        echo ("<TD>" . htmlspecialchars($resultado['edad']) . "</TD>\n");
        echo ("<TD>" . htmlspecialchars($resultado['curso']) . "</TD>\n");
        echo ("<TD>" . htmlspecialchars($resultado['padres']) . "</TD>\n");
        echo ("<TD><img src='uploads/" . htmlspecialchars($resultado['foto']) . "' alt='foto del alumno'><br>" . htmlspecialchars($resultado['foto']) . "</TD>\n");
        echo "<td> <a href='eliminar_alumnos.php?id_alumnos=" . htmlspecialchars($resultado['id_alumnos']) . "'>Borrar</a> </td>\n";
        echo ("</TR>\n");   
    }  
} else {                                                                                                     
    echo ("<TR><TD colspan='7'>No hay alumnos disponibles</TD></TR>\n");    
}

echo "</table>\n";
echo '<a href="socios.php">Volver al menú</a>';
?>

</body>
</html>
