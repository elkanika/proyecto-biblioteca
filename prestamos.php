<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestamos</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
         function actualizaPagina() {
            var i = document.forms.selecciona.usuario.selectedIndex;
            var usuario = document.forms.selecciona.usuario.options[i].value;
            if (usuario === 'Todas') {
                window.location = 'prestamos.php';
            } else {
                window.location = 'prestamos.php?usuario=' + usuario;
            }
        }
    </script>
</head>
<body>
<h1 style="color: rgb(48, 118, 248);">Prestamos registrados</h1>

<?php
include 'db_config.php';

echo "<table class='tabla-lista'>";

echo "<form name='selecciona' action='prestamos.php' method='POST'>\n";
echo "<p>Mostrar prestamos por usuario:\n";
echo "<select name='usuario' onchange='actualizaPagina()'>\n";

echo "<option value='Todas'>Todas</option>\n";


// Obtener los usuarios únicos de la tabla `prestamos`
$instruccion = "SELECT DISTINCT usuario FROM prestamos";
$consulta = mysqli_query($conn, $instruccion) or die("Fallo en la consulta: " . mysqli_error($conn));

$usuario = isset($_REQUEST['usuario']) ? $_REQUEST['usuario'] : null;
$selected = $usuario ? $usuario : "Todas";
while ($row = mysqli_fetch_array($consulta)) {
    $usuario_db = $row['usuario'];
    if ($usuario_db == $selected)
        echo "<option value='$usuario_db' selected>$usuario_db</option>\n";
    else
        echo "<option value='$usuario_db'>$usuario_db</option>\n";
}

echo "</select></p>\n";
echo "</form>\n";

echo "<!-- Usuario seleccionado: " . htmlspecialchars($usuario) . " -->\n";

$instruccion = "SELECT * FROM prestamos";
if ($usuario && $usuario != "Todas")
    $instruccion .= " WHERE usuario ='$usuario'";
$instruccion .= " ORDER BY id ASC";

$consulta = mysqli_query($conn, $instruccion) or die("Fallo en la consulta: " . mysqli_error($conn));

$nfilas = mysqli_num_rows($consulta);
if ($nfilas > 0) {
    echo "<tr>\n";
    echo "<th>Usuario</th>\n";
    echo "<th>Fecha de prestamo</th>\n";
    echo "<th>Fecha de devolución</th>\n";
    echo "<th>Libro prestado</th>\n";
    echo "<th>Estado</th>\n";
    echo "<th>Fecha de multa</th>\n";
    echo "<th>Motivo de la multa</th>\n";
    echo "<th>Monto de la multa</th>\n";
    echo "<th>Eliminar</th>\n";
    echo "</tr>\n";

    while ($resultado = mysqli_fetch_array($consulta)) {
        $usuario = $resultado['usuario'];

        // Consulta para obtener el nombre y apellido del usuario por su dni (usuario)
        $query_usuario = "SELECT nombre, apellido FROM Alumnos WHERE dni='$usuario'
                          UNION
                          SELECT nombre, apellido FROM Profesores WHERE dni='$usuario'";
        
        $resultado_usuario = mysqli_query($conn, $query_usuario) or die("Fallo en la consulta de usuario: " . mysqli_error($conn));
        $nombre_usuario = "Desconocido";
        if ($resultado_usuario && mysqli_num_rows($resultado_usuario) > 0) {
            $row_usuario = mysqli_fetch_assoc($resultado_usuario);
            $nombre_usuario = $row_usuario['nombre'] . " " . $row_usuario['apellido'];
        } else {
            echo "<!-- No se encontró usuario con dni: $usuario -->\n";
        }

        echo "<tr>\n";
        echo "<td>" . htmlspecialchars($nombre_usuario) . "</td>\n";
        echo "<td>" . htmlspecialchars($resultado['fecha_prestamo']) . "</td>\n";
        echo "<td>" . htmlspecialchars($resultado['fecha_devolucion']) . "</td>\n";
        echo "<td>" . htmlspecialchars($resultado['libro_prestado']) . "</td>\n";
        echo "<td>" . htmlspecialchars($resultado['estado']) . "</td>\n";
        echo "<td>" . htmlspecialchars($resultado['fecha_multa']) . "</td>\n";
        echo "<td>" . htmlspecialchars($resultado['motivo_multa']) . "</td>\n";
        echo "<td>" . htmlspecialchars($resultado['monto_multa']) . "</td>\n";
        echo "<td> <a href='eliminar_prestamos.php?id=" . htmlspecialchars($resultado['id']) . "'>Borrar</a> </td>\n";
        echo "</tr>\n";
    }
} else {
    echo "<tr><td colspan='9'>No hay prestamos actualmente</td></tr>\n";    
}

echo "</table>\n";
echo '<a href="dashboard.html">Volver al menú</a>';
?>

</body>
</html>
