<?php
session_start();
require '../vendor/autoload.php';

setcookie('banner', '1', time() + 365 * 84600);
return volver();
