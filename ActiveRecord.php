<?php
require 'comunes/auxiliar.php';
require 'Saludo.php';
require 'Buscable.php';
require 'Contable.php';

abstract class ActiveRecord implements Buscable, Contable
{
    use Saludo;

    public ?int $id = null;
    private static ?PDO $pdo = null;

    protected abstract static function tabla(): string;
    protected abstract function insertar(): void;
    protected abstract function modificar(): void;

    public function __construct(array $propiedades = [])
    {
        foreach ($propiedades as $k => $v) {
            $this->$k = $v;
        }
    }

    /**
     * Devuelve un array con todas las instancias correspondientes a la tabla.
     *
     * @return static[]
     */
    public static function todos(): array
    {
        $pdo = static::pdo();
        $tabla = static::tabla();
        $sent = $pdo->query("SELECT * FROM $tabla");
        return $sent->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public static function lista(): array
    {
        $filas = static::todos();
        $res = [];

        foreach ($filas as $fila) {
            $res[$fila->id] = (string) $fila;
        }

        return $res;
    }

    public static function buscar_por_id(string $id): ?static
    {
        $pdo = static::pdo();
        $tabla = static::tabla();
        $sent = $pdo->prepare("SELECT * FROM $tabla WHERE id = :id");
        $sent->execute([':id' => $id]);
        return $sent->fetchObject(static::class) ?: null;
    }

    public function guardar(): void
    {
        if (isset($this->id)) {
            $this->modificar();
        } else {
            $this->insertar();
        }
    }

    public function borrar(): bool
    {
        $pdo = static::pdo();
        $tabla = static::tabla();
        $sent = $pdo->prepare("DELETE FROM $tabla WHERE id = :id");
        $res = $sent->execute([':id' => $this->id]);
        $this->id = null;
        return $res;
    }

    protected static function pdo(): PDO
    {
        static::$pdo = static::$pdo ?? conectar();
        return static::$pdo;
    }
}
