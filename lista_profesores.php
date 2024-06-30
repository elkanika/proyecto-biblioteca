<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesores</title>
    <script>
        function actualizaPagina() {
            var i = document.forms.selecciona.dni.selectedIndex;
            var dni = document.forms.selecciona.dni.options[i].value;
            if (dni === 'Todos') {
                window.location = 'lista_profesores.php';
            } else {
                window.location = 'lista_profesores.php?dni=' + dni;
            }
        }
    </script>
</head>
<body>
<h1 style="color: rgb(48, 118, 248);">Consulta de profesores</h1>

<?php 
include 'db_config.php'; 

echo "<table class='tabla-lista'>";

echo ("<FORM NAME='selecciona' ACTION='lista_profesores.php' METHOD='POST'>\n");
echo ("<P>Mostrar profesores con el DNI:\n");
echo ("<SELECT NAME='dni' ONCHANGE='actualizaPagina()'>\n");

// Añadir la opción "Todos"
echo "<option value='Todos'>Todos</option>\n";

// Consultar DNIs de profesores
$instruccion = "SELECT DISTINCT dni FROM Profesores";
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
$instruccion = "SELECT * FROM Profesores";
if ($dni && $dni != "Todos") {
    $instruccion .= " WHERE dni ='$dni'";
}
$instruccion .= " ORDER BY id_profesores ASC";

// Realizar la consulta a la base de datos
$consulta = mysqli_query($conn, $instruccion) or die("Fallo en la consulta: " . mysqli_error($conn));

$nfilas = mysqli_num_rows($consulta);
if ($nfilas > 0) {
    echo ("<TR>\n");
    echo ("<TH>Nombre</TH>\n");
    echo ("<TH>Apellido</TH>\n");
    echo ("<TH>Dni</TH>\n");
    echo ("<TH>Edad</TH>\n");
    echo ("<TH>Teléfono</TH>\n");
    echo ("<TH>Email</TH>\n");
    echo ("<TH>Materias</TH>\n");
    echo ("<TH>Cursos</TH>\n");
    echo ("<TH>Foto</TH>\n");
    echo "<th>Eliminar</th>\n";
    echo ("</TR>\n");

    while ($resultado = mysqli_fetch_array($consulta)) {
        echo ("<TR>\n");
        echo ("<TD>" . htmlspecialchars($resultado['nombre']) . "</TD>\n");
        echo ("<TD>" . htmlspecialchars($resultado['apellido']) . "</TD>\n");
        echo ("<TD>" . htmlspecialchars($resultado['dni']) . "</TD>\n");
        echo ("<TD>" . htmlspecialchars($resultado['edad']) . "</TD>\n");
        echo ("<TD>" . htmlspecialchars($resultado['telefono']) . "</TD>\n");
        echo ("<TD>" . htmlspecialchars($resultado['email']) . "</TD>\n");
        echo ("<TD>" . htmlspecialchars($resultado['materias']) . "</TD>\n");
        echo ("<TD>" . htmlspecialchars($resultado['cursos']) . "</TD>\n");
        echo ("<TD><img src='uploads/" . htmlspecialchars($resultado['foto']) . "' alt='foto del profesor'><br>" . htmlspecialchars($resultado['foto']) . "</TD>\n");
        echo "<td> <a href='eliminar_profesores.php?id_profesores=" . htmlspecialchars($resultado['id_profesores']) . "'>Borrar</a> </td>\n";
        echo ("</TR>\n");   
    }  
} else {                                                                                                     
    echo ("<TR><TD colspan='8'>No hay profesores disponibles</TD></TR>\n");    
}

echo "</table>\n";
echo '<a href="socios.php">Volver al menú</a>';
?>

</body>
</html>
