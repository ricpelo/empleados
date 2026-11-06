<?php
require 'comunes/auxiliar.php';

class Departamento
{
    public ?int $id = null;
    public string $codigo;
    public string $denominacion;

    private static ?PDO $pdo = null;

    private function insertar(): void
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

    private function modificar(): void
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

    public function guardar(): void
    {
        if (isset($this->id)) {
            $this->modificar();
        } else {
            $this->insertar();
        }
    }

    public function borrar()
    {
        $pdo = Departamento::pdo();
        $sent = $pdo->prepare('DELETE FROM departamentos WHERE id = :id');
        $sent->execute([':id' => $this->id]);
        $this->id = null;
    }

    public static function pdo(): PDO
    {
        Departamento::$pdo = Departamento::$pdo ?? conectar();
        return Departamento::$pdo;
    }
}
