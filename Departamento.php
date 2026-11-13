<?php
require 'ActiveRecord.php';

class Departamento extends ActiveRecord
{
    public string $codigo;
    public string $denominacion;

    #[Override]
    protected static function tabla(): string
    {
        return 'departamentos';
    }

    public function __toString(): string
    {
        return "(" . $this->codigo . ") " . $this->denominacion;
    }

    #[Override]
    public function buscar(string $s): string
    {
        if (mb_strpos($this->codigo, $s) || mb_strpos($this->denominacion, $s)) {
            return (string) $this;
        }
        return '';
    }

    /**
     * Devuelve todos los departamentos que tengan una cierta denominación.
     *
     * @return Departamento[]
     */
    public static function buscar_por_denominacion(string $denominacion): array
    {
        $pdo = static::pdo();
        $sent = $pdo->prepare("SELECT *
                                 FROM departamentos
                                WHERE translate(denominacion, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU') ILIKE :denominacion");
        $sent->execute([':denominacion' => "%$denominacion%"]);
        return $sent->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    #[Override]
    protected function insertar(): void
    {
        $pdo = Departamento::pdo();
        $sent = $pdo->prepare('INSERT INTO departamentos (codigo, denominacion)
                                VALUES (:codigo, :denominacion)
                                RETURNING id');
        $sent->execute([
            ':codigo' => $this->codigo,
            ':denominacion' => $this->denominacion,
        ]);
        $this->id = $sent->fetchColumn();
    }

    #[Override]
    protected function modificar(): void
    {
        $pdo = Departamento::pdo();
        $sent = $pdo->prepare('UPDATE departamentos
                                  SET codigo = :codigo,
                                      denominacion = :denominacion
                                WHERE id = :id');
        $sent->execute([
            ':id' => $this->id,
            ':codigo' => $this->codigo,
            ':denominacion' => $this->denominacion,
        ]);
    }

    #[Override]
    public function contar(): int
    {
        return 2;
    }
}
