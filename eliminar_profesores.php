<?php
include 'db_config.php';
session_start();

if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

if (isset($_GET['id_profesores'])) {
    $id = intval($_GET['id_profesores']);

    $stmt = $conn->prepare("DELETE FROM Profesores WHERE id_profesores = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: lista_profesores.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
