<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    require 'auxiliar.php';

    $codigo = obtener_post('codigo');
    $denominacion = obtener_post('denominacion');
    $pdo = conectar();
    $errores = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        validar_codigo($codigo, $pdo, $errores);
        validar_denominacion($denominacion, $errores);
        if (empty($errores)) {
            insertar_departamento($pdo, $codigo, $denominacion);
            return volver();
        } else {
            mostrar_errores($errores);
        }
    }
    ?>
    <form action="" method="post">
        <div>
            <label>
                Código:
                <input type="text" name="codigo" value="<?= $codigo ?>" />
            </label>
        </div>
        <div>
            <label>
                Denominación:
                <input type="text" name="denominacion" value="<?= $denominacion ?>" />
            </label>
        </div>
        <button type="submit">Insertar</button>
    </form>
</body>
</html>
