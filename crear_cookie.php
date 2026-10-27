<?php
session_start();
require 'comunes/auxiliar.php';

setcookie('banner', '1', time() + 365 * 84600);
return volver();
