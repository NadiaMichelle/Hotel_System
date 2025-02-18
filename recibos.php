<?php
session_start();
require 'config.php';

// Configuración de paginación
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual > 1) ? ($pagina_actual * $registros_por_pagina) - $registros_por_pagina : 0;

// Obtener datos de filtros si existen
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : '';
$huesped = isset($_GET['huesped']) ? $_GET['huesped'] : '';
$habitacion = isset($_GET['habitacion']) ? $_GET['habitacion'] : '';

// Construir la consulta SQL con filtros
$sql = "SELECT r.*, h.nombre as nombre_huesped, GROUP_CONCAT(e.nombre SEPARATOR ', ') as elementos_nombres
        FROM recibos r 
        JOIN huespedes h ON r.id_huesped = h.id
        LEFT JOIN detalles_reserva dr ON r.id = dr.reserva_id
        LEFT JOIN elementos e ON dr.elemento_id = e.id
        WHERE 1=1";

$params = [];

if (!empty($check_in)) {
    $sql .= " AND r.check_in >= :check_in";
    $params[':check_in'] = $check_in;
}

if (!empty($check_out)) {
    $sql .= " AND r.check_out <= :check_out";
    $params[':check_out'] = $check_out;
}

if (!empty($huesped)) {
    $sql .= " AND h.nombre LIKE :huesped";
    $params[':huesped'] = '%' . $huesped . '%';
}

if (!empty($habitacion)) {
    $sql .= " AND e.nombre LIKE :habitacion";
    $params[':habitacion'] = '%' . $habitacion . '%';
}

$sql .= " GROUP BY r.id
          LIMIT $inicio, $registros_por_pagina";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$reservaciones = $stmt->fetchAll();

// Calcular el total de registros para la paginación
$sql_count = "SELECT COUNT(*) FROM recibos r 
              JOIN huespedes h ON r.id_huesped = h.id
              LEFT JOIN detalles_reserva dr ON r.id = dr.reserva_id
              LEFT JOIN elementos e ON dr.elemento_id = e.id
              WHERE 1=1";

if (!empty($check_in)) {
    $sql_count .= " AND r.check_in >= :check_in";
}

if (!empty($check_out)) {
    $sql_count .= " AND r.check_out <= :check_out";
}

if (!empty($huesped)) {
    $sql_count .= " AND h.nombre LIKE :huesped";
}

if (!empty($habitacion)) {
    $sql_count .= " AND e.nombre LIKE :habitacion";
}

$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute($params);
$total_reservas = $stmt_count->fetchColumn();
$paginas = ceil($total_reservas / $registros_por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Reservaciones</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
                     :root {
            --color-primario: #2c3e50;
            --color-secundario: #3498db;
            --color-blanco: #ffffff;
        }

        body { 
            font-family: 'Segoe UI', system-ui, sans-serif; 
            margin: 0; 
            padding: 0; 
            background: #f8f9fa; 
            position: relative;
            min-height: 100vh;
        }

        /* Navbar móvil */
        .navbar-movil {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: var(--color-primario);
            color: var(--color-blanco);
            padding: 15px 20px;
            z-index: 1000;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .menu-btn {
            font-size: 24px;
            cursor: pointer;
            padding: 5px;
        }

        /* Menú lateral */
        .menu-lateral {
            width: 250px;
            height: 100vh;
            background: var(--color-primario);
            padding-top: 20px;
            position: fixed;
            left: 0;
            top: 0;
            color: var(--color-blanco);
            transition: transform 0.3s ease;
            z-index: 999;
        }

        .menu-lateral h2 {
            text-align: center;
            margin: 20px 0;
            font-size: 1.5rem;
        }

        .menu-lateral ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-lateral ul li {
            padding: 15px 20px;
            transition: background 0.3s;
        }

        .menu-lateral ul li:hover {
            background: #1a252f;
        }

        .menu-lateral ul li a {
            color: var(--color-blanco);
            text-decoration: none;
            display: block;
            font-size: 1rem;
        }

        .menu-lateral ul li i {
            margin-right: 10px;
            width: 20px;
        }

        /* Contenido principal */
        .contenido {
            margin-left: 250px;
            padding: 30px;
            transition: margin 0.3s;
        }

        h1 {
            color: var(--color-primario);
            margin-bottom: 30px;
            font-size: 2rem;
        }

        /* Tabla responsiva */
        .tabla-contenedor {
            background: var(--color-blanco);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            text-align: center;
        }

        th {
            background: var(--color-primario);
            color: var(--color-blanco);
            font-weight: 600;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        /* Acciones */
        .acciones a {
            text-decoration: none;
            margin: 0 5px;
            padding: 8px 12px;
            border-radius: 5px;
            color: var(--color-blanco);
            font-size: 0.9rem;
            transition: opacity 0.3s;
            display: inline-block;
        }

        .btn-editar { background: #f1c40f; }
        .btn-eliminar { background: #e74c3c; }
        .btn-imprimir { background: #3498db; }

        .acciones a:hover {
            opacity: 0.9;
        }

        /* Paginación */
        .paginacion {
            margin-top: 25px;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 5px;
        }

        .paginacion a {
            padding: 8px 15px;
            margin: 0 3px;
            text-decoration: none;
            color: var(--color-blanco);
            background: var(--color-primario);
            border-radius: 5px;
            transition: background 0.3s;
        }

        .paginacion a.activo {
            background: var(--color-secundario);
            cursor: default;
        }

        .paginacion a:hover:not(.activo) {
            background: #1a252f;
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .navbar-movil {
                display: flex;
            }

            .menu-lateral {
                transform: translateX(-100%);
                width: 80%;
                max-width: 300px;
                padding-top: 70px;
            }

            .menu-lateral.active {
                transform: translateX(0);
            }

            .contenido {
                margin-left: 0;
                padding: 20px;
                padding-top: 80px;
            }

            h1 {
                font-size: 1.5rem;
                margin-bottom: 25px;
            }

            .tabla-contenedor {
                padding: 15px;
            }

            th, td {
                padding: 10px;
                font-size: 0.9rem;
            }

            .acciones a {
                padding: 6px 10px;
                margin: 2px;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .paginacion a {
                padding: 6px 12px;
                font-size: 0.85rem;
            }

            .menu-lateral ul li {
                padding: 12px 15px;
            }

            .menu-lateral h2 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>

<!-- Navbar móvil -->
<div class="navbar-movil">
    <div class="menu-btn" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </div>
    <h1>Reservaciones</h1>
</div>

<!-- Menú Lateral -->
<nav class="menu-lateral">
    <h2>Hotel</h2>
    <ul>
        <li><a href="index.php"><i class="fas fa-home"></i>Inicio</a></li>
        <li><a href="habitaciones.php"><i class="fas fa-bed"></i>Habitaciones</a></li>
        <li><a href="huespedes.php"><i class="fas fa-users"></i>Huéspedes</a></li>
        <li><a href="Crear_Recibo.php"><i class="fas fa-pen-alt"></i>Crear reservas</a></li>
        <li><a href="recibos.php"><i class="fas fa-file-invoice"></i>Reservas</a></li>
        <li><a href="index.php"><i class="fas fa-sign-out-alt"></i>Salir</a></li>
    </ul>
</nav>

<!-- Contenido Principal -->
<main class="contenido">
    <h1>Lista de Reservaciones</h1>
    
    <!-- Filtros -->
    <div class="filtros">
        <h2>Filtros</h2>
        <form id="formulario-filtros" method="get">
            <label for="check_in">Check-in:</label>
            <input type="date" id="check_in" name="check_in" value="<?= htmlspecialchars($check_in) ?>">

            <label for="check_out">Check-out:</label>
            <input type="date" id="check_out" name="check_out" value="<?= htmlspecialchars($check_out) ?>">

            <label for="huesped">Huésped:</label>
            <input type="text" id="huesped" name="huesped" value="<?= htmlspecialchars($huesped) ?>">

            <label for="habitacion">Habitación:</label>
            <input type="text" id="habitacion" name="habitacion" value="<?= htmlspecialchars($habitacion) ?>">

            <button type="submit">Aplicar Filtros</button>
            <button type="button" onclick="resetFiltros()">Resetear Filtros</button>
        </form>
    </div>

    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Huésped</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Habitaciones/Servicios</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservaciones as $reserva): ?>
                <tr>
                    <td><?= $reserva['id'] ?></td>
                    <td><?= htmlspecialchars($reserva['nombre_huesped']) ?></td>
                    <td><?= date('d/m/Y', strtotime($reserva['check_in'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($reserva['check_out'])) ?></td>
                    <td><?= htmlspecialchars($reserva['elementos_nombres']) ?></td>
                    <td>$<?= number_format($reserva['total_pagar'], 2) ?></td>
                    <td><?= htmlspecialchars($reserva['estado']) ?></td>
                    <td class="acciones">
                        <a href="editar_reserva.php?id=<?= $reserva['id'] ?>" class="btn-editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="eliminar_reserva.php?id=<?= $reserva['id'] ?>" class="btn-eliminar" 
                           onclick="return confirm('¿Seguro que deseas cancelar esta reservación?')">
                            <i class="fas fa-times"></i>
                        </a>
                        <a href="javascript:void(0)" onclick="imprimirRecibo(<?= $reserva['id'] ?>)" class="btn-imprimir">
                            <i class="fas fa-print"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="paginacion">
        <?php if ($pagina_actual > 1): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual - 1])) ?>">&laquo; Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $paginas; $i++): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>" class="<?= ($pagina_actual == $i) ? 'activo' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($pagina_actual < $paginas): ?>
            <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual + 1])) ?>">Siguiente &raquo;</a>
        <?php endif; ?>
    </div>
</main>

<script>
    // Funcionalidad del menú móvil
    function toggleMenu() {
        document.querySelector('.menu-lateral').classList.toggle('active');
    }

    // Cerrar menú al hacer clic fuera
    document.addEventListener('click', function(e) {
        const menu = document.querySelector('.menu-lateral');
        const navbar = document.querySelector('.navbar-movil');
        
        if (!menu.contains(e.target) && !navbar.contains(e.target)) {
            menu.classList.remove('active');
        }
    });

    // Función para imprimir
    function imprimirRecibo(id) {
        window.open(`imprimir_recibo.php?id=${id}`, '_blank').print();
    }

    // Resetear filtros
    function resetFiltros() {
        window.location.href = 'recibos.php';
    }
</script>

</body>
</html>