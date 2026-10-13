<?php

function obtener_get(string $par): ?string
{
    return (isset($_GET[$par])) ? trim($_GET[$par]) : null;
}
