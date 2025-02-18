<?php
session_start();
require 'config.php';

$error = ''; // Para capturar errores
$success = ''; // Para mostrar mensajes de éxito

// Verifica si se recibió un ID de huésped a editar
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos actuales del huésped
    $stmt = $pdo->prepare('SELECT * FROM huespedes WHERE id = ?');
    $stmt->execute([$id]);
    $huesped = $stmt->fetch();

    if (!$huesped) {
        $error = "Huésped no encontrado.";
    }
} else {
    $error = "No se recibió un ID válido.";
}

// Lógica para guardar la edición del huésped
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar_edicion'])) {
    $rfc = $_POST['rfc'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $tipo_huesped = $_POST['tipo_huesped'];
    $logo = '';

    // Manejo del logo
    if ($_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $target_file = $target_dir . basename($_FILES["logo"]["name"]);
        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
            $logo = $target_file;
        } else {
            $error = "Error al subir el logo.";
        }
    }

    // Actualizar los datos del huésped en la base de datos
    $stmt = $pdo->prepare('UPDATE huespedes SET rfc = ?, nombre = ?, telefono = ?, correo = ?, tipo_huesped = ?, logo = ? WHERE id = ?');
    if ($stmt->execute([$rfc, $nombre, $telefono, $correo, $tipo_huesped, $logo, $id])) {
        $success = "Huésped actualizado con éxito.";
        header('Location: huespedes.php');
        exit;
    } else {
        $error = "Hubo un error al actualizar el huésped.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device="width=device-width, initial-scale=1.0">
    <title>Editar Huésped</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Paleta de colores */
        :root {
            --primary-color: #2c3e50; /* Azul oscuro */
            --secondary-color: #34495e; /* Azul medio */
            --background-color: #ecf0f1; /* Blanco */
            --text-color: #333333; /* Gris oscuro */
            --light-gray: #bdc3c7; /* Gris claro */
            --success-color: #2ecc71; /* Verde */
            --error-color: #e74c3c; /* Rojo */
        } 

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Estilos para el contenedor principal */
        .container {
            background-color: var(--background-color);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        /* Estilos para los encabezados */
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--primary-color);
        }

        /* Estilos para las etiquetas */
        label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-color);
            font-weight: bold;
        }

        /* Estilos para los campos de entrada */
        input[type="text"], input[type="email"], input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid var(--light-gray);
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="file"]:focus {
            border-color: var(--primary-color);
        }

        /* Estilos para el botón de guardar */
        button[type="submit"] {
            background-color: var(--success-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #27ae60;
        }

        /* Estilos para el botón de cancelar */
        button[type="button"] {
            background-color: var(--error-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        button[type="button"]:hover {
            background-color: #c0392b;
        }

        /* Estilos para los mensajes de error y éxito */
        .error-message {
            color: var(--error-color);
            text-align: center;
            margin-bottom: 15px;
        }

        .success-message {
            color: var(--success-color);
            text-align: center;
            margin-bottom: 15px;
        }

        /* Estilos para el selector de tipo de huésped */
        .form-group {
            margin-bottom: 15px;
        }

        /* Estilos para el campo de carga de logo */
        .logo-upload {
            display: none;
        }

        /* Mostrar el campo de carga de logo si el tipo de huésped es empresa */
        #tipo_huesped[value="empresa"] + .logo-upload {
            display: block;
        }
  /* Estilos para el menú lateral */
  .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-size: 18px;
            padding: 10px;
        }

        .sidebar ul li a i {
            margin-right: 10px;
            font-size: 20px;
        }


    </style>
</head>
<body>
<aside class="sidebar">
        <h2>Menú</h2>
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> Inicio</a></li>
            <li><a href="habitaciones.php"><i class="fas fa-bed"></i> Habitaciones</a></li>
            <li><a href="huespedes.php"><i class="fas fa-users"></i> Huéspedes</a></li>
            <li><a href="Crear_Recibo.php"><i class="fas fa-pen-alt"></i> Crear Reservación</a></li>
            <li><a href="recibos.php"><i class="fas fa-file-invoice"></i> Reservas</a></li>
            <li><a href="index.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
        </ul>
    </aside>
    <div class="container">
        <h1>Editar Huésped</h1>

        <!-- Mostrar mensaje de error o éxito -->
        <?php if (!empty($error)) { echo "<p class='error-message'>$error</p>"; } ?>
        <?php if (!empty($success)) { echo "<p class='success-message'>$success</p>"; } ?>

        <form method="post" enctype="multipart/form-data" class="edit-form">
            <div class="form-group">
                <label for="tipo_huesped">Tipo de Huésped:</label>
                <select name="tipo_huesped" id="tipo_huesped" onchange="toggleFields()" required>
                    <option value="persona" <?php if ($huesped['tipo_huesped'] == 'persona') echo 'selected'; ?>>Persona</option>
                    <option value="empresa" <?php if ($huesped['tipo_huesped'] == 'empresa') echo 'selected'; ?>>Empresa</option>
                </select>
            </div>

            <div class="form-group">
                <label for="rfc">RFC:</label>
                <input type="text" id="rfc" name="rfc" value="<?= htmlspecialchars($huesped['rfc']) ?>" required>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($huesped['nombre']) ?>" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($huesped['telefono']) ?>">
            </div>

            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($huesped['correo']) ?>">
            </div>

            <div class="form-group logo-upload">
                <label for="logo">Logo:</label>
                <input type="file" id="logo" name="logo">
            </div>

            <button type="submit" name="guardar_edicion" class="btn btn-guardar">Guardar</button>
            <button type="button" class="btn btn-cancelar" onclick="window.location.href='huespedes.php'">Cancelar</button>
        </form>
    </div>

    <script>
        function toggleFields() {
            const tipoHuesped = document.getElementById('tipo_huesped').value;
            const logoUpload = document.querySelector('.logo-upload');
            if (tipoHuesped === 'empresa') {
                logoUpload.style.display = 'block';
            } else {
                logoUpload.style.display = 'none';
            }
        }

        // Inicializar el estado del campo de carga de logo
        window.onload = toggleFields;
    </script>
</body>
</html>
