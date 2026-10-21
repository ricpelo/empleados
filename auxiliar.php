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

function buscar_departamento_por_id(PDO $pdo, string $id): array
{
    $sent = $pdo->prepare("SELECT *
                             FROM departamentos
                            WHERE id = :id");
    $sent->execute([':id' => $id]);
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

function validar_codigo(string $codigo, PDO $pdo, array &$errores, ?string $id = null): void
{
    if ($codigo === '') {
        $errores['codigo'][] = 'El código no puede ser vacío.';
    } elseif (!ctype_digit($codigo)) {
        $errores['codigo'][] = 'El código debe ser un número entero positivo.';
    } elseif ($codigo < 0 || $codigo > 99) {
        $errores['codigo'][] = 'El código debe estar comprendido entre 0 y 99.';
    } else {
        $sql = 'SELECT COUNT(*)
                  FROM departamentos
                 WHERE codigo = :codigo';
        $execute = [':codigo' => $codigo];
        if ($id !== null) {
            $sql .= ' AND id != :id';
            $execute[':id'] = $id;
        }
        $sent = $pdo->prepare($sql);
        $sent->execute($execute);
        if ($sent->fetchColumn() > 0) {
            $errores['codigo'][] = 'Ya existe otro departamento con ese código.';
        }
    }
}

function validar_denominacion(string $denominacion, array &$errores): void
{
    if ($denominacion === '') {
        $errores['denominacion'][] = 'La denominación no puede ser vacía.';
    } elseif (mb_strlen($denominacion) > 255) {
        $errores['denominacion'][] = 'La denominación es demasiado larga.';
    }
}

function mostrar_errores(string $campo, array $errores): void
{
    if (isset($errores[$campo])):
        ?>
        <ul class="error">
            <?php foreach ($errores[$campo] as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach ?>
        </ul>
        <?php
    endif;
}

function insertar_departamento(PDO $pdo, string $codigo, string $denominacion): void
{
    $sent = $pdo->prepare('INSERT INTO departamentos (codigo, denominacion)
                           VALUES (:codigo, :denominacion)');
    $sent->execute([
        ':codigo' => $codigo,
        ':denominacion' => $denominacion,
    ]);
}

function modificar_departamento(PDO $pdo, string $id, string $codigo, string $denominacion): void
{
    $sent = $pdo->prepare('UPDATE departamentos
                              SET codigo = :codigo,
                                  denominacion = :denominacion
                            WHERE id = :id');
    $sent->execute([
        ':codigo' => $codigo,
        ':denominacion' => $denominacion,
        ':id' => $id,
    ]);
}

function css_campo_error(string $campo, array $errores): string
{
    return (isset($errores[$campo])) ? 'class="campo-error"' : '';
}
