<?php
$servername = "localhost";
$username = "c1621890_biblio";
$password = "wuGO93zowe";
$dbname = "c1621890_biblio";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

