<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Hotel Puesta del Sol</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Estilos generales */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Animación del degradado */
        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Aplicación de la animación al fondo */
        .content, .welcome-box {
            background: linear-gradient(-45deg, #ff9a9e, #fad0c4, #fad0c4, #a18cd1);
            background-size: 400% 400%;
            animation: gradient-animation 15s ease infinite;
        }

        /* Contenido principal */
        .content {
            flex: 1;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Estilos para la caja de bienvenida */
        .welcome-box {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            max-width: 900px;
            width: 100%;
            background: rgba(255, 255, 255, 0.8);
        }

        .welcome-box .text {
            flex: 1;
            padding-right: 20px;
        }

        .welcome-box .text h1 {
            margin-top: 0;
            color: #2c3e50;
        }

        .welcome-box .text p {
            color: #34495e;
            font-size: 18px;
        }

        .welcome-box .highlight {
            color: #e74c3c;
            font-weight: bold;
        }

        .welcome-box .welcome-img {
            width: 300px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Estilos para el menú inferior */
        .bottom-menu {
            background-color: #2c3e50;
            padding: 15px 0;
        }

        .bottom-menu ul {
            list-style: none;
            display: flex;
            justify-content: space-around;
            margin: 0;
            padding: 0;
        }

        .bottom-menu ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 16px;
        }

        .bottom-menu ul li a i {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .bottom-menu .logout-btn {
            color: #e74c3c;
        }

        /* Estilos para el menú lateral (si es necesario) */
        /* Puedes agregar estilos para un menú lateral si decides implementarlo en el futuro */
    </style>
</head>
<body>

    <!-- Contenido principal (Bienvenida) -->
    <main class="content">
        <div class="welcome-box">
            <div class="text">
                <h1>Bienvenido al <span class="highlight">Sistema Hotel Puesta del Sol</span></h1>
                <p>Gestión de habitaciones, huéspedes, servicios y reservaciones</p>
            </div>
            <img src="background.jpg" alt="Hotel en la playa" class="welcome-img">
        </div>
    </main>

    <!-- Menú abajo (más arribita) -->
    <nav class="bottom-menu">
        <ul>
            <li><a href="habitaciones.php"><i class="fas fa-bed"></i> Habitaciones y Servicios</a></li>
            <li><a href="huespedes.php"><i class="fas fa-users"></i> Huéspedes</a></li>
            <li><a href="Crear_Recibo.php"><i class="fas fa-pen-alt"></i> Crear Reservacion</a></li>
            <li><a href="recibos.php"><i class="fas fa-file-invoice"></i> Reservaciones</a></li>
            <li><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Salir</a></li>
        </ul>
    </nav>

</body>
</html>
