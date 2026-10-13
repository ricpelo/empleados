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

    $pdo = new PDO('pgsql:host=localhost;dbname=empleados', 'empleados', 'empleados');

    $codigo = obtener_get('codigo');

    if ($codigo === null || $codigo === '') {
        $sent = $pdo->query('SELECT COUNT(*) FROM departamentos');
        $count = $sent->fetchColumn();
        if ($count == 0) {
            ?>
            <h3>La tabla está vacía</h3>
            <?php
        } else {
            $sent = $pdo->query('SELECT * FROM departamentos');
        }
    } else {                       // No es la primera vez que se entra
        $sent = $pdo->prepare("SELECT COUNT(*)
                                 FROM departamentos
                                WHERE codigo::text = :codigo");
        $sent->execute([':codigo' => $codigo]);
        $count = $sent->fetchColumn();
        if ($count == 0) {
            ?>
            <h3>No hay ningún departamento con ese código.</h3>
            <?php
        } else {
            $sent = $pdo->prepare("SELECT *
                                     FROM departamentos
                                    WHERE codigo::text = :codigo");
            $sent->execute([':codigo' => $codigo]);
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
                <?php foreach ($sent as $fila): ?>
                    <tr>
                        <td><?= $fila['codigo'] ?></td>
                        <td><?= $fila['denominacion'] ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <p>Número de registros: <?= $count ?></p>
    <?php endif ?>
    <a href="buscar.php">Buscar de nuevo</a>
</body>
</html>
