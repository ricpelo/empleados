<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .error {
            font-size: x-small;
            color: red;
            padding-left: 1em;
        }
        .campo-error {
            color: red;
            border-color: red;
        }
    </style>
</head>
<body>
    <?php
    require '../comunes/auxiliar.php';

    $id = obtener_get('id');
    $pdo = conectar();
    $errores = [];

    if (empty($id) ||
        !ctype_digit($id) ||
        empty($fila = buscar_departamento_por_id($pdo, $id))) {
        return volver();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $codigo = obtener_post('codigo');
        $denominacion = obtener_post('denominacion');
        validar_codigo($codigo, $pdo, $errores, $id);
        validar_denominacion($denominacion, $errores);
        if (empty($errores)) {
            modificar_departamento($pdo, $id, $codigo, $denominacion);
            return volver();
        }
    } else {
        $codigo = $fila['codigo'];
        $denominacion = $fila['denominacion'];
    }
    ?>
    <form action="" method="post">
        <div>
            <label <?= css_campo_error('codigo', $errores) ?> >
                Código:
                <input
                    type="text"
                    name="codigo"
                    value="<?= $codigo ?>"
                    <?= css_campo_error('codigo', $errores) ?>
                >
            </label>
            <?php mostrar_errores('codigo', $errores) ?>
        </div>
        <div>
            <label <?= css_campo_error('denominacion', $errores) ?> >
                Denominación:
                <input
                    type="text"
                    name="denominacion"
                    value="<?= $denominacion ?>"
                    <?= css_campo_error('denominacion', $errores) ?>
                >
            </label>
            <?php mostrar_errores('denominacion', $errores) ?>
        </div>
        <button type="submit">Modificar</button>
        <a href="index.php">Volver</a>
    </form>
</body>
</html>
