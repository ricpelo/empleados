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

    $denominacion = obtener_get('denominacion');
    ?>
    <fieldset>
        <legend>Buscar</legend>
        <form action="" method="get">
            <div>
                <label>
                    Denominación:
                    <input type="text" name="denominacion"
                        value="<?= $denominacion ?>" />
                </label>
            </div>
            <button type="submit">Buscar</button>
        </form>
    </fieldset>
    <?php
    $pdo = conectar();

    $denominacion = str_replace(
        ["á", "é", "í", "ó", "ú"],
        ["a", "e", "i", "o", "u"],
        strtolower($denominacion)
    );

    if ($denominacion === '') {
        $filas = todos_departamentos($pdo);
        $count = count($filas);
        if ($count == 0) {
            ?>
            <h3>La tabla está vacía</h3>
            <?php
        }
    } else {
        $filas = buscar_departamento_por_denominacion($pdo, $denominacion);
        $count = count($filas);
        if ($count == 0) {
            ?>
            <h3>No hay ningún departamento con esa denominación.</h3>
            <?php
        }
    }
    ?>
    <?php if ($count > 0): ?>
        <table border="1">
            <thead>
                <th>Código</th>
                <th>Denominación</th>
                <th>Acciones</th>
            </thead>
            <tbody>
                <?php foreach ($filas as $fila): ?>
                    <tr>
                        <td><?= $fila['codigo'] ?></td>
                        <td><?= $fila['denominacion'] ?></td>
                        <td>
                            <form action="hacer_borrado.php" method="post">
                                <input type="hidden" name="id" value="<?= $fila['id'] ?>">
                                <button type="submit">Borrar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <p>Número de registros: <?= $count ?></p>
    <?php endif ?>
    <a href="insertar.php">Insertar un nuevo departamento</a>
</body>
</html>
