<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertar empleado</title>
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

    $numero = obtener_post('numero');
    $nombre = obtener_post('nombre');
    $apellidos = obtener_post('apellidos');
    $fechanac = obtener_post('fechanac');
    $codpostal = obtener_post('codpostal');
    $telefono = obtener_post('telefono');
    $departamento_id = obtener_post('departamento_id');
    $pdo = conectar();
    $errores = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        validar_numero($numero, $pdo, $errores);
        validar_nombre($nombre, $errores);
        validar_apellidos($apellidos, $errores);
        validar_fechanac($fechanac, $errores);
        validar_codpostal($codpostal, $errores);
        validar_telefono($telefono, $errores);
        validar_departamento_id($departamento_id, $pdo, $errores);
        if (empty($errores)) {
            insertar_empleado($pdo, compact(
                'numero',
                'nombre',
                'apellidos',
                'fechanac',
                'codpostal',
                'telefono',
                'departamento_id'
            ));
            return volver();
        }
    }
    ?>
    <form action="" method="post">
        <div>
            <label <?= css_campo_error('numero', $errores) ?> >
                Número*:
                <input
                    type="text"
                    name="numero"
                    value="<?= $numero ?>"
                    <?= css_campo_error('numero', $errores) ?>
                >
            </label>
            <?php mostrar_errores('numero', $errores) ?>
        </div>
        <div>
            <label <?= css_campo_error('nombre', $errores) ?> >
                Nombre*:
                <input
                    type="text"
                    name="nombre"
                    value="<?= $nombre ?>"
                    <?= css_campo_error('nombre', $errores) ?>
                >
            </label>
            <?php mostrar_errores('nombre', $errores) ?>
        </div>
        <div>
            <label <?= css_campo_error('apellidos', $errores) ?> >
                Apellidos:
                <input
                    type="text"
                    name="apellidos"
                    value="<?= $apellidos ?>"
                    <?= css_campo_error('apellidos', $errores) ?>
                >
            </label>
            <?php mostrar_errores('apellidos', $errores) ?>
        </div>
        <div>
            <label <?= css_campo_error('fechanac', $errores) ?> >
                Fecha de nacimiento*:
                <input
                    type="date"
                    name="fechanac"
                    value="<?= $fechanac ?>"
                    <?= css_campo_error('fechanac', $errores) ?>
                >
            </label>
            <?php mostrar_errores('fechanac', $errores) ?>
        </div>
        <div>
            <label <?= css_campo_error('codpostal', $errores) ?> >
                Código postal*:
                <input
                    type="text"
                    name="codpostal"
                    value="<?= $codpostal ?>"
                    <?= css_campo_error('codpostal', $errores) ?>
                >
            </label>
            <?php mostrar_errores('codpostal', $errores) ?>
        </div>
        <div>
            <label <?= css_campo_error('telefono', $errores) ?> >
                Teléfono:
                <input
                    type="text"
                    name="telefono"
                    value="<?= $telefono ?>"
                    <?= css_campo_error('telefono', $errores) ?>
                >
            </label>
            <?php mostrar_errores('telefono', $errores) ?>
        </div>
        <div>
            <label <?= css_campo_error('departamento_id', $errores) ?> >
                Departamento*:
                <select name="departamento_id">
                    <?php foreach (lista_departamentos($pdo) as $k => $v): ?>
                        <option value="<?= $k ?>">
                            <?= $v ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </label>
            <?php mostrar_errores('departamento_id', $errores) ?>
        </div>
        <button type="submit">Insertar</button>
        <a href="index.php">Volver</a>
    </form>
</body>
</html>
