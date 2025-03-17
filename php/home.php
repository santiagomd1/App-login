<?php
session_start(); // Iniciar una nueva sesión o reanudar la existente
require 'database.php'; // Conexión a la base de datos

if (isset($_GET['id_us']) && !empty($_GET['id_us'])) { // Verificar el id_us en concreto
    $usuario_id = $_GET['id_us'];

    // Consulta para obtener los detalles del respectivo usuario (id y correo)
    $query = $pdo->prepare("SELECT * FROM usuarios WHERE id_us = ?");
    $query->execute([$usuario_id]);
    $usuario = $query->fetch(); // Almacenamiento de la consulta en la variable ($usuario)
    $correo = $usuario['ucorreo']; // Almacenamiento del correo en la respectiva variable

    if (!$usuario) { // Mensaje de error y terminación del proceso
        echo "Usuario no encontrado.";
        exit();
    }
} else {
    echo "El ID de usuario no fue dado."; // Mensaje en caso de que no posea un id asociado
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title> <!--Nombre de la página -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Recurso de Bootstrapp (CSS) -->
    <link rel="stylesheet" href="../css/home.css">
    <script>
        // Función de confirmación para regresar al Inicio
        function Inicio() {
            if (confirm("¿Desea retornar al Inicio? Se terminará su sesión actual.")) {
                window.location.href = "../index.html";
                exit();
            }
        }
        // Función de confirmación para eliminar la cuenta
        function eliminarUsuario(id) {
            if (confirm("¿Está seguro de que desea eliminar su cuenta? Es una acción que no se puede deshacer.")) {
                const form = document.createElement('form'); // Creación de 'form' en el DOM
                form.method = 'POST'; // Configuración del método del formulario como tipo POST
                form.action = 'eliminar_u.php'; // Redirección y ejecución del contenido del archivo respectivo

                const input = document.createElement('input'); // Creación del campo 'input' para almacenar el identificador del usuario a eliminar
                input.type = 'hidden'; // Ocultar para el usuario
                input.name = 'id_us'; // Asignar de nombre
                input.value = id; // // Establecer el valor como el identificador del usuario respectivo

                form.appendChild(input); // Añadir un campo oculto (input)
                document.body.appendChild(form); // Añadirlo al HTML en el body, para que pueda ser enviado

                form.submit(); // Envío del formulario
            }
        }
    </script>
</head>

<body>
    <img src="../recursos/usuario.jpg" alt="Imagen de usuario" class="background"> <!-- Insertar imagen de fondo de Usuario -->
    <!-- Mensaje de bienvenida -->
    <div class="content">
        <h3>Bienvenido a</h1>
            <h1>
                <!-- Nombre del aplicativo -->
                <span class="blue">M</span>
                <span class="blue">Y</span>
                <span>&nbsp;</span>
                <span class="blue">A</span>
                <span class="blue">P</span>
                <span class="blue">P</span>
            </h1>
            <p>¡Hola <strong><?php echo htmlspecialchars($correo); ?></strong>!</p>
            <!-- Botones -->
            <p>Elija la acción que desea realizar:</p>
            <!-- Botón retorno al Inicio (index.php) con la respectiva función -->
            <button onclick="Inicio()" class="btn btn-primary">Inicio <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-check" viewBox="0 0 16 16">
                    <path d="M7.293 1.5a1 1 0 0 1 1.414 0L11 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l2.354 2.353a.5.5 0 0 1-.708.708L8 2.207l-5 5V13.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 2 13.5V8.207l-.646.647a.5.5 0 1 1-.708-.708z" />
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.707l.547.547 1.17-1.951a.5.5 0 1 1 .858.514" />
                </svg>
            </button>
            <!-- Botón de cerrar sesión (eliminación del usuario) con la respectiva función -->
            <button onclick="eliminarUsuario(<?php echo $usuario_id; ?>)" class="btn btn-danger">Cerrar sesión <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-x" viewBox="0 0 16 16">
                    <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4" />
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m-.646-4.854.646.647.646-.647a.5.5 0 0 1 .708.708l-.647.646.647.646a.5.5 0 0 1-.708.708l-.646-.647-.646.647a.5.5 0 0 1-.708-.708l.647-.646-.647-.646a.5.5 0 0 1 .708-.708" />
                </svg>
            </button>
    </div>
</body>
</html>

