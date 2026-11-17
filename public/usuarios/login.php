<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Login</title>
</head>

<body>
    <?php
    require '../comunes/auxiliar.php';

    $email = obtener_post('email');
    $password = obtener_post('password');
    $pdo = conectar();
    $errores = [];

    cabecera();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sent = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
        $sent->execute([':email' => $email]);
        $fila = $sent->fetch();
        if ($fila && password_verify($password, $fila['password'])) {
            $_SESSION['login'] = $email;
            $_SESSION['info'] = 'Login correcto.';
            return volver();
        } else {
            echo "<h2>Credenciales incorrectas.</h2>";
        }
    }

    ?>
    <div class="container mx-auto">
        <form action="" method="post" class="mt-20 max-w-sm mx-auto fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
            <label class="fieldset">
                <span class="label">Correo electrónico</span>
                <input name="email" type="email" class="input validator" required value="<?= $email ?>" />
                <span class="validator-hint hidden">Obligatorio</span>
            </label>

            <label class="fieldset">
                <span class="label">Contraseña</span>
                <input name="password" type="password" class="input validator" required value="<?= $password ?>" />
                <span class="validator-hint hidden">Obligatorio</span>
            </label>

            <button class="btn btn-neutral mt-4" type="submit">Iniciar sesión</button>
        </form>
    </div>
</body>
</html>
