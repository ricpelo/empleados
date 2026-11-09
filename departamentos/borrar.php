<?php
session_start();

require '../Departamento.php';

// if (!esta_logueado() || $_SESSION['login'] != 'admin@admin.com') {
    // $_SESSION['info'] = 'Debe iniciar sesión para poder borrar un departamento.';
    // return volver_departamento();
// }

$id = obtener_post('id');
$_csrf = obtener_post('_csrf');

if (!comprobar_csrf($_csrf)) {
    return volver();
}

if ($id === '') {
    return volver();
}

$departamento = Departamento::buscar_por_id($id);
$res = $departamento?->borrar();

if ($res) {
    $_SESSION['info'] = 'El departamento se ha borrado correctamente.';
} else {
    $_SESSION['info'] = 'Ocurrió un error al borrar el departamento.';
}

return volver();
