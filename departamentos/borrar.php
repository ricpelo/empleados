<?php
session_start();

require '../comunes/auxiliar.php';

if (!esta_logueado() || $_SESSION['login'] != 'admin@admin.com') {
    $_SESSION['info'] = 'Debe iniciar sesión para poder borrar un departamento.';
    return volver_departamento();
}

$id = obtener_post('id');

if ($id === '') {
    return volver();
}

$pdo = conectar();
$sent = $pdo->prepare('DELETE FROM departamentos WHERE id = :id');
$res = $sent->execute([':id' => $id]);

if ($res) {
    $_SESSION['info'] = 'El departamento se ha borrado correctamente.';
} else {
    $_SESSION['info'] = 'Ocurrió un error al borrar el departamento.';
}

return volver();
