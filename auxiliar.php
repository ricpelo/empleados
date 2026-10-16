<?php

function obtener_get(string $par): string
{
    return (isset($_GET[$par])) ? trim($_GET[$par]) : '';
}

function obtener_post(string $par): string
{
    return (isset($_POST[$par])) ? trim($_POST[$par]) : '';
}

function conectar(): PDO
{
    return new PDO('pgsql:host=localhost;dbname=empleados', 'empleados', 'empleados');
}

function todos_departamentos(PDO $pdo): array
{
    $sent = $pdo->query('SELECT * FROM departamentos');
    return $sent->fetchAll();
}

function buscar_departamento_por_codigo(PDO $pdo, string $codigo): array
{
    $sent = $pdo->prepare("SELECT *
                             FROM departamentos
                            WHERE codigo::text = :codigo");
    $sent->execute([':codigo' => $codigo]);
    return $sent->fetch() ?: [];
}

function buscar_departamento_por_denominacion(PDO $pdo, string $denominacion): array
{
    $sent = $pdo->prepare("SELECT *
                             FROM departamentos
                            WHERE translate(denominacion, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU') ILIKE :denominacion");
    $sent->execute([':denominacion' => "%$denominacion%"]);
    return $sent->fetchAll();
}

function volver()
{
    header('Location: index.php');
}
