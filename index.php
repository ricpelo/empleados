<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="get">
        <label>
            Buscar por código:
            <input type="text" name="codigo" />
        </label>
        <button type="submit">Buscar</button>
    </form>
    <?php
    require 'auxiliar.php';

    $pdo = conectar();
    $codigo = obtener_get('codigo');

    if ($codigo === null || $codigo === '') {
        $filas = todos_departamentos($pdo);
        $count = count($filas);
        if ($count == 0) {
            ?>
            <h3>La tabla está vacía</h3>
            <?php
        }
    } else {
        $fila = buscar_departamento_por_codigo($pdo, $codigo);
        $count = empty($fila) ? 0 : 1;
        $filas = [$fila];
        if ($count == 0) {
            ?>
            <h3>No hay ningún departamento con ese código.</h3>
            <?php
        }
    }
    ?>
    <?php if ($count > 0): ?>
        <table border="1">
            <thead>
                <th>Código</th>
                <th>Denominación</th>
            </thead>
            <tbody>
                <?php foreach ($filas as $fila): ?>
                    <tr>
                        <td><?= $fila['codigo'] ?></td>
                        <td><?= $fila['denominacion'] ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <p>Número de registros: <?= $count ?></p>
    <?php endif ?>
</body>
</html>
