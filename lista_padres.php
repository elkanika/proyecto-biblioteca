<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Padres</title>

    <script type='text/javascript'>
        function actualizaPagina() {
            // Obtener el índice de la opción seleccionada en el formulario
            var i = document.forms.selecciona.dni.selectedIndex;
            // Obtener el valor del DNI seleccionado
            var dni = document.forms.selecciona.dni.options[i].value;
            
            // Redireccionar a la misma página con el DNI seleccionado como parámetro
            var url = window.location.origin + window.location.pathname;
            if (dni === 'Todos') {
                window.location = url; // Redirigir sin parámetro
            } else {
                window.location = url + '?dni=' + dni;
            }
        }
    </script>
</head>
<body>
<h1 style="color: rgb(48, 118, 248);">Consulta de Padres</h1>

<?php 
include 'db_config.php'; 

echo "<table class='tabla-lista'>";

echo ("<form name='selecciona' action='lista_Padres.php' method='post'>\n");
echo ("<p>Mostrar Padres con el DNI:</p>\n");
echo ("<select name='dni' onchange='actualizaPagina()'>\n");

echo "<option value='Todos'>Todos</option>\n";
// Consulta para obtener todos los DNIs
$instruccion = "SELECT dni FROM Padres";
$consulta = mysqli_query($conn, $instruccion) or die("Fallo en la consulta");

$dniSeleccionado = isset($_REQUEST['dni']) ? $_REQUEST['dni'] : '';
while ($row = mysqli_fetch_array($consulta)) {
    $dni_db = $row['dni'];
    if ($dni_db == $dniSeleccionado)
        echo ("   <option value='$dni_db' selected>$dni_db</option>\n");
    else
        echo ("   <option value='$dni_db'>$dni_db</option>\n");
}

echo ("</select>\n");
echo ("</form>\n");

// Construir la consulta a la base de datos
$instruccion = "SELECT * FROM Padres";
if (!empty($dniSeleccionado) && $dniSeleccionado != "Todos")
    $instruccion .= " WHERE dni ='$dniSeleccionado'";
$instruccion .= " ORDER BY id ASC";

// Realizar la consulta a la base de datos
$consulta = mysqli_query($conn, $instruccion) or die("Fallo en la consulta");

$nfilas = mysqli_num_rows($consulta);
if ($nfilas > 0) {
    echo ("<tr>\n");
    echo ("<th>Nombre</th>\n");
    echo ("<th>Apellido</th>\n");
    echo ("<th>Edad</th>\n");
    echo ("<th>Hijo/os</th>\n");
    echo ("<th>Telefono</th>\n");
    echo ("<th>Email</th>\n");
    echo "<th>Eliminar</th>\n";
    echo ("</tr>\n");

    while ($resultado = mysqli_fetch_array($consulta)) {
        echo ("<tr>\n");
        echo ("<td>" . $resultado['nombre'] . "</td>\n");
        echo ("<td>" . $resultado['apellido'] . "</td>\n");
        echo ("<td>" . $resultado['edad'] . "</td>\n");
        echo ("<td>" . $resultado['hijos'] . "</td>\n");
        echo ("<td>" . $resultado['telefono'] . "</td>\n");
        echo ("<td>" . $resultado['email'] . "</td>\n");
        echo "<td> <a href='eliminar_padres.php?id=" . htmlspecialchars($resultado['id']) . "'>Borrar</a> </td>\n";
        echo ("</tr>\n");   
    }  
    echo "</table>"; // Cerrar la etiqueta de la tabla después de imprimir los resultados
} else {
    echo ("No hay Padres disponibles");    
}
?>
<a href="socios.php">Volver al menú</a> <!-- Colocar el enlace fuera del bloque PHP -->
</body>
</html>
