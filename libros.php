<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libros</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function actualizaPagina() {
            i = document.forms.selecciona.titulo.selectedIndex;
            titulo = document.forms.selecciona.titulo.options[i].value;
            window.location = 'libros.php?titulo=' + titulo;
        }

        function updateQuantity(id, change) {
            var currentQuantity = parseInt($("#cantidad-" + id).text())
            if (currentQuantity + change >= 0) {
                $.ajax({
                    url: 'update_quantity.php',
                    type: 'POST',
                    data: {
                        id: id,
                        change: change
                    },
                    success: function(response) {
                        $('#cantidad-' + id).text(response);
                    }
                });
            }
        }
    </script>
</head>
<body>
<h1 style="color: rgb(48, 118, 248);">Libros registrados</h1>

<?php 
include 'db_config.php'; 

echo "<table class='tabla-lista'>";

echo ("<FORM NAME='selecciona' ACTION='libros.php' METHOD='POST'>\n");
echo ("<P>Mostrar libros por titulo:\n");
echo ("<SELECT NAME='titulo' ONCHANGE='actualizaPagina()'>\n");

$instruccion = "SELECT titulo FROM libros";
$consulta = mysqli_query($conn, $instruccion) or die("Fallo en la consulta");

$titulo = $_REQUEST['titulo'];
if (isset($titulo))
   $selected = $titulo;
else
   $selected = "Todas";
while ($row = mysqli_fetch_array($consulta)) {
   $titulo_db = $row['titulo'];
   if ($titulo_db == $selected)
       echo ("<OPTION VALUE='$titulo_db' SELECTED>$titulo_db\n");
   else
       echo ("<OPTION VALUE='$titulo_db'>$titulo_db\n");
}

echo ("</SELECT></P>\n");
echo ("</FORM>\n");

$instruccion = "SELECT * FROM libros";
if (isset($titulo) && $titulo != "Todas")
   $instruccion = $instruccion . " WHERE titulo ='$titulo'";
$instruccion = $instruccion . " ORDER BY id ASC";

$consulta = mysqli_query($conn, $instruccion) or die("Fallo en la consulta");

$nfilas = mysqli_num_rows($consulta);
if ($nfilas > 0) {
   echo ("<TR>\n");
   echo ("<TH>Titulo</TH>\n");
   echo ("<TH>Autor</TH>\n");
   echo ("<TH>ISBN</TH>\n");
   echo ("<TH>Editorial</TH>\n");
   echo ("<TH>Publicación</TH>\n");
   echo ("<TH>Categoría</TH>\n");
   echo ("<TH>Cantidad disponible</TH>\n");
   echo ("<TH>Adquisición</TH>\n");
   echo ("<TH>Ubicación</TH>\n");
   echo ("<TH>Eliminar</TH>\n");
   echo ("</TR>\n");

   while ($resultado = mysqli_fetch_array($consulta)) {
       echo ("<TR>\n");
       echo ("<TD>" . $resultado['titulo'] . "</TD>\n");
       echo ("<TD>" . $resultado['autor'] . "</TD>\n");
       echo ("<TD>" . $resultado['isbn'] . "</TD>\n");
       echo ("<TD>" . $resultado['editorial'] . "</TD>\n");
       echo ("<TD>" . $resultado['publicacion'] . "</TD>\n");
       echo ("<TD>" . $resultado['categoria'] . "</TD>\n");
       echo ("<TD><button onclick=\"updateQuantity(" . $resultado['id'] . ", -1)\">-</button> <span id='cantidad-" . $resultado['id'] . "'>" . $resultado['cantidad'] . "</span> <button onclick=\"updateQuantity(" . $resultado['id'] . ", 1)\">+</button></TD>\n");
       echo ("<TD>" . $resultado['adquisicion'] . "</TD>\n");
       echo ("<TD>" . $resultado['ubicacion'] . "</TD>\n");
       echo ("<TD> <a href='eliminar_libros.php?id={$resultado['id']}'>Borrar</a> </TD>\n");
       echo ("</TR>\n");   
   }  
} else {
    echo ("No hay libros disponibles");    
}
?>
</table>
<a href="dashboard.html">Volver al menú</a>
</body>
</html>
