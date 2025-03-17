<?php
session_start(); // Iniciar una nueva sesión o reanudar la existente
require 'database.php'; // Conexión a la base de datos

// Verificar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['ucorreo']) && isset($_POST['contrasena'])) { // Condicional de ambas variables que componen el usuario
        $usuario = $_POST['ucorreo'];
        $contraseña = $_POST['contrasena'];

        // Consultar la base de datos para autenticar al usuario
        $query = $pdo->prepare("SELECT id_us, contrasena FROM usuarios WHERE ucorreo = ?");
        $query->execute([$usuario]);
        $user = $query->fetch(); // Almacenamiento de la consulta en la variable

        if ($user) {
            // Verificación de la contraseña
            if (password_verify($contraseña, $user['contrasena'])) {
                // Almacenar los datos del usuario en la sesión
                $_SESSION['ucorreo'] = $usuario;
                $_SESSION['id_us'] = $user['id_us'];

                // Mostrar un mensaje de bienvenida y redirigir al Home
                echo "<script>
                    alert('¡Inicio de sesión exitoso, bienvenido nuevamente {$usuario}!');
                    window.location.href = 'home.php?id_us={$user['id_us']}';
                </script>";
                exit();
            } else { // Mensaje de error de contraseña
                
                // Redirige al archivo de login
                echo "<script>
                    alert('Contraseña incorrecta, por favor ingresela nuevamente.');
                    window.location.href = '../html/login.html';
                </script>";
                exit();
            }
        } else { // Si el usuario no existe, se realiza la creación
            if (strlen($contraseña) < 6) { // Comprobación de que la contraseña tenga longitud mínima de 6 caracteres
                $error = "La contraseña debe tener al menos 6 caracteres.";
            } else {
                // Hashear la contraseña antes de guardarla
                $hash_contraseña = password_hash($contraseña, PASSWORD_DEFAULT);

                // Insertar el nuevo usuario en la base de datos
                $insert_query = $pdo->prepare("INSERT INTO usuarios (ucorreo, contrasena) VALUES (?, ?)");
                $insert_query->execute([$usuario, $hash_contraseña]);

                // Obtener el ID del usuario recién creado
                $id_nuevo_usuario = $pdo->lastInsertId();

                // Mostrar un mensaje de creación y redirigir al usuario al Home con su respectivo ID
                echo "<script>
                    alert('Usuario creado de manera exitosa.');
                    window.location.href = '../php/home.php?id_us={$id_nuevo_usuario}';
                </script>";
                exit();
            }
        }
    } else {
        $error = "Por favor complete todos los campos."; // Mensaje en caso de que alguno de los dos campos no esté digitado
    }
}
?>