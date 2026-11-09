<?php
require 'comunes/auxiliar.php';

class Departamento
{
    public ?int $id = null;
    public string $codigo;
    public string $denominacion;

    private static ?PDO $pdo = null;

    public function __construct(?string $codigo = null, ?string $denominacion = null)
    {
        if ($codigo !== null) {
            $this->codigo = $codigo;
        }

        if ($denominacion !== null) {
            $this->denominacion = $denominacion;
        }
    }

    public static function todos(): array
    {
        $pdo = Departamento::pdo();
        $sent = $pdo->query('SELECT * FROM departamentos');
        return $sent->fetchAll(PDO::FETCH_CLASS, Departamento::class);
    }

    public static function lista(): array
    {
        $filas = Departamento::todos();
        $res = [];

        foreach ($filas as $fila) {
            $res[$fila->id] = "(" . $fila->codigo . ") " . $fila->denominacion;
        }

        return $res;
    }

    public static function buscar_por_denominacion(string $denominacion): array
    {
        $pdo = Departamento::pdo();
        $sent = $pdo->prepare("SELECT *
                                FROM departamentos
                                WHERE translate(denominacion, 'áéíóúÁÉÍÓÚ', 'aeiouAEIOU') ILIKE :denominacion");
        $sent->execute([':denominacion' => "%$denominacion%"]);
        return $sent->fetchAll(PDO::FETCH_CLASS, Departamento::class);
    }

    public static function buscar_por_id(string $id): ?Departamento
    {
        $pdo = Departamento::pdo();
        $sent = $pdo->prepare("SELECT *
                                FROM departamentos
                                WHERE id = :id");
        $sent->execute([':id' => $id]);
        return $sent->fetchObject(Departamento::class) ?: null;
    }

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

    public function borrar(): bool
    {
        $pdo = Departamento::pdo();
        $sent = $pdo->prepare('DELETE FROM departamentos WHERE id = :id');
        $res = $sent->execute([':id' => $this->id]);
        $this->id = null;
        return $res;
    }

    public static function pdo(): PDO
    {
        Departamento::$pdo = Departamento::$pdo ?? conectar();
        return Departamento::$pdo;
    }
}
