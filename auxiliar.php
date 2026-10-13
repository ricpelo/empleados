<?php

function obtener_get(string $par): ?string
{
    return (isset($_GET[$par])) ? trim($_GET[$par]) : null;
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
