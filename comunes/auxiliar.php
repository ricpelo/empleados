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

function todos_empleados(PDO $pdo): array
{
    $sent = $pdo->query('SELECT e.*, codigo, denominacion
                           FROM empleados e JOIN departamentos d
                             ON departamento_id = d.id');
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
    header('Location: /index.php');
}

function volver_departamento()
{
    header('Location: /departamentos/index.php');
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
    $res = $sent->execute([
        ':codigo' => $codigo,
        ':denominacion' => $denominacion,
    ]);

    if ($res) {
        $_SESSION['info'] = 'El departamento se ha insertado correctamente.';
    } else {
        $_SESSION['info'] = 'Ocurrió un error al insertar el departamento.';
    }
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

function lista_departamentos(PDO $pdo): array
{
    $filas = todos_departamentos($pdo);
    $res = [];

    foreach ($filas as $fila) {
        $res[$fila['id']] = "(" . $fila['codigo'] . ") " . $fila['denominacion'];
    }

    return $res;
}

function validar_numero(string $numero, PDO $pdo, array &$errores, ?string $id = null)
{
    if ($numero === '') {
        $errores['numero'][] = 'El número no puede ser vacío.';
    } elseif (!ctype_digit($numero)) {
        $errores['numero'][] = 'El número debe ser un número entero positivo.';
    } elseif ($numero < 0 || $numero > 9999) {
        $errores['numero'][] = 'El número debe estar comprendido entre 0 y 9999.';
    } else {
        $sql = 'SELECT COUNT(*)
                  FROM empleados
                 WHERE numero = :numero';
        $execute = [':numero' => $numero];
        if ($id !== null) {
            $sql .= ' AND id != :id';
            $execute[':id'] = $id;
        }
        $sent = $pdo->prepare($sql);
        $sent->execute($execute);
        if ($sent->fetchColumn() > 0) {
            $errores['numero'][] = 'Ya existe otro empleado con ese número.';
        }
    }
}

function validar_nombre(string $nombre, array &$errores): void
{
    if ($nombre === '') {
        $errores['nombre'][] = 'El nombre no puede ser vacío.';
    } elseif (mb_strlen($nombre) > 255) {
        $errores['nombre'][] = 'El nombre es demasiado largo.';
    }
}

function validar_apellidos(string &$apellidos, array &$errores): void
{
    if ($apellidos === '') {
        $apellidos = NULL;
    } elseif (mb_strlen($apellidos) > 255) {
        $errores['apellidos'][] = 'Los apellidos son demasiado largos.';
    }
}

function validar_fechanac(string $fechanac, array &$errores): void
{
    if ($fechanac === '') {
        $errores['fechanac'][] = 'La fecha de nacimiento no puede ser vacía.';
    } elseif (DateTime::createFromFormat('Y-m-d', $fechanac) === false) {
        $errores['fechanac'][] = 'La fecha es incorrecta.';
    }
}

function validar_codpostal(string $codpostal, array &$errores)
{
    if ($codpostal === '') {
        $errores['codpostal'][] = 'El código postal no puede ser vacío.';
    } elseif (!ctype_digit($codpostal)) {
        $errores['codpostal'][] = 'El código postal debe ser un número entero positivo.';
    } elseif ($codpostal < 0 || $codpostal > 99999) {
        $errores['codpostal'][] = 'El código postal debe estar comprendido entre 0 y 99999.';
    }
}

function validar_telefono(string &$telefono, array &$errores)
{
    if ($telefono === '') {
        $telefono = NULL;
    } elseif (!ctype_digit($telefono)) {
        $errores['telefono'][] = 'El teléfono debe ser un número entero positivo.';
    } elseif ($telefono < 0 || $telefono > 99999999999999) {
        $errores['telefono'][] = 'El teléfono debe tener como máximo 14 dígitos.';
    }
}

function validar_departamento_id(string $departamento_id, PDO $pdo, array &$errores)
{
    if ($departamento_id === '') {
        $errores['departamento_id'][] = 'Falta el departamento del empleado.';
    } elseif (!ctype_digit($departamento_id)) {
        $errores['departamento_id'][] = 'El departamento es incorrecto.';
    } elseif (!buscar_departamento_por_id($pdo, $departamento_id)) {
        $errores['departamento_id'][] = 'El departamento no existe.';
    }
}

function insertar_empleado(PDO $pdo, array $campos): void
{
    extract($campos);
    $sent = $pdo->prepare(
        'INSERT INTO empleados (
            numero,
            nombre,
            apellidos,
            fechanac,
            codpostal,
            telefono,
            departamento_id
        ) VALUES (
            :numero,
            :nombre,
            :apellidos,
            :fechanac,
            :codpostal,
            :telefono,
            :departamento_id
        )'
    );
    $sent->execute([
        ':numero' => $numero,
        ':nombre' => $nombre,
        ':apellidos' => $apellidos,
        ':fechanac' => $fechanac,
        ':codpostal' => $codpostal,
        ':telefono' => $telefono,
        ':departamento_id' => $departamento_id,
    ]);
}

function cabecera()
{
    ?>
    <div align="right">
        <a href="/empleados/index.php">Empleados</a>
        <a href="/departamentos/">Departamentos</a>
        <?php if (isset($_SESSION['login'])): ?>
            <?= $_SESSION['login'] ?>
            <a href="/usuarios/logout.php">Logout</a>
        <?php else: ?>
            <a href="/usuarios/login.php">Login</a>
        <?php endif ?>
    </div>
    <hr>
    <?php if (isset($_SESSION['info'])): ?>
        <div style="background-color: azure">
            <?= $_SESSION['info'] ?>
            <?php unset($_SESSION['info']) ?>
        </div>
    <?php endif;
}

function esta_logueado(): bool
{
    return isset($_SESSION['login']);
}

function hh(string $cadena): string
{
    return htmlspecialchars($cadena);
}

function token_csrf(): string
{
    if (!isset($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function campo_csrf()
{
    ?>
    <input type="hidden" name="_csrf" value="<?= token_csrf() ?>">
    <?php
}
