<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $pdo = new PDO('pgsql:host=localhost;dbname=empleados', 'empleados', 'empleados');

    $codigo = trim($_GET['codigo']);

    $sent = $pdo->prepare("SELECT * FROM departamentos WHERE codigo = :codigo");
    $sent->execute([':codigo' => $codigo]);
    ?>
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
</body>
</html>
