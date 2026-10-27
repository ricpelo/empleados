<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú principal</title>
    <style>
        #banner {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%; /* Opcional: para que ocupe todo el ancho */
            z-index: 1000; /* Opcional: para asegurarte de que esté por encima de otros elementos */
            background-color: antiquewhite;
        }
    </style>
</head>
<body>
    <?php
    require 'comunes/auxiliar.php';
    cabecera();
    ?>
    <ul>
        <li><a href="empleados/index.php">Gestión de empleados</a></li>
        <li><a href="departamentos/index.php">Gestión de departamentos</a></li>
    </ul>
    <?php if (!isset($_COOKIE['banner'])): ?>
        <div id="banner">
            <h3>Este sitio usa cookies. <a href="/crear_cookie.php">Cerrar</a></h3>
        </div>
    <?php endif ?>
</body>
</html>
