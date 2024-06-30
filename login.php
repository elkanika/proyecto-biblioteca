<?php
include 'db_config.php';

// Obtener los valores enviados por el formulario
$usuario = $_POST['usuario'];
$palabra_secreta = $_POST['palabra_secreta'];

if ($usuario == "admin" && $palabra_secreta == "murialdo2021"){
    session_start();
    header("Location: dashboard.html");        
}

