<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <form action="" method="post">
        <div>
            <label>
                Email:
                <input
                    type="text"
                    name="email"
                    value="<?= $email ?>"
                >
            </label>
        </div>
        <div>
            <label>
                Contraseña:
                <input
                    type="password"
                    name="password"
                    value="<?= $password ?>"
                >
            </label>
        </div>
        <button type="submit">Login</button>
    </form>
</body>
</html>
