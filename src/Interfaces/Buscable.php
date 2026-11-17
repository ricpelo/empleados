<?php
namespace App\Interfaces;

interface Buscable
{
    public function buscar(string $s): string;
}
