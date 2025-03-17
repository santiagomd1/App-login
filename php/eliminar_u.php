<?php
session_start(); // Iniciar una nueva sesión o reanudar la existente
require 'database.php'; // Conexión a la base de datos

if (isset($_POST['id_us'])) { // Verificación de que el id del usuario haya sido enviado en un método POST
    $id_us = $_POST['id_us']; // Extracción del id y almacenamiento en la respectiva variable

    // Eliminar el usuario de la base de datos
    $query = $pdo->prepare("DELETE FROM usuarios WHERE id_us = ?");
    $query->execute([$id_us]);

    // Cerrar sesión y redirigir al Inicio
    session_destroy(); // Destruye la sesión
    echo "<script>
        alert('Usuario eliminado con éxito.');
        window.location.href = '../index.html';
    </script>";
    exit();
} else { // Mensaje en caso de que el usuario a eliminar no posea un ID
    echo "Error: No fue dado el ID del usuario respectivo."; 
}
?>
