<?php
include 'db_config.php';
session_start();

if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

if (isset($_GET['id_alumnos'])) {
    $id = intval($_GET['id_alumnos']);

    $stmt = $conn->prepare("DELETE FROM Alumnos  WHERE id_alumnos = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: lista_alumnos.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
