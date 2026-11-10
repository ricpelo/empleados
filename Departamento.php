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

    /**
     * Devuelve todos los departamentos que tengan una cierta denominación.
     *
     * @return Departamento[]
     */
    public static function buscar_por_denominacion(string $denominacion): array
    {
        $pdo = Departamento::pdo();
        $sent = $pdo->prepare("SELECT *
                                FROM departamentos
                                WHERE translate(denominacion, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU') ILIKE :denominacion");
        $sent->execute([':denominacion' => "%$denominacion%"]);
        return $sent->fetchAll(PDO::FETCH_CLASS, Departamento::class);
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
}
