<?php
namespace App\A;

use App\Interfaces\Buscable;
use App\Interfaces\Contable;
use Override;
use App\Utilidades\Saludo;

class Archivo implements Buscable, Contable
{
    use Saludo;

    public string $nombre;
    public array $contenido;

    public function __construct(string $nombre)
    {
        $this->nombre = $nombre;
        $this->contenido = [];
    }

    public function cargar()
    {
        $this->contenido = file($this->nombre);
    }

    #[Override]
    public function buscar(string $s): string
    {
        foreach ($this->contenido as $linea) {
            if (mb_strpos($linea, $s) !== false) {
                return $linea;
            }
        }
        return '';
    }

    #[Override]
    public function contar(): int
    {
        return count($this->contenido);
    }
}
