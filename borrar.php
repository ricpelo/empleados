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

    $id = obtener_get('id');
    ?>
    <h3>¿Está seguro?</h3>
    <form action="hacer_borrado.php" method="post">
        <input type="hidden" name="id" value="<?= $id ?>" />
        <button type="submit">Sí</button>
        <a href="index.php">No</a>
    </form>
</body>
</html>
