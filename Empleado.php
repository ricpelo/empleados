<?php
require 'ActiveRecord.php';

class Empleado extends ActiveRecord
{
    public string $numero;
    public string $nombre;
    public ?string $apellidos;
    public string $fechanac;
    public string $codpostal;
    public ?string $telefono;
    public int $departamento_id;

    #[Override]
    protected static function tabla(): string
    {
        return 'empleados';
    }

    #[Override]
    protected function insertar(): void
    {
        $pdo = Empleado::pdo();
        $sent = $pdo->prepare('INSERT INTO empleados (
                                    numero,
                                    nombre,
                                    apellidos,
                                    fechanac,
                                    codpostal,
                                    telefono,
                                    departamento_id
                                ) VALUES (
                                    :numero,
                                    :nombre,
                                    :apellidos,
                                    :fechanac,
                                    :codpostal,
                                    :telefono,
                                    :departamento_id
                                )
                                RETURNING id');
        $sent->execute([
            ':numero' => $this->numero,
            ':nombre' => $this->nombre,
            ':apellidos' => $this->apellidos,
            ':fechanac' => $this->fechanac,
            ':codpostal' => $this->codpostal,
            ':telefono' => $this->telefono,
            ':departamento_id' => $this->departamento_id,
        ]);
        $this->id = $sent->fetchColumn();
    }

    #[Override]
    protected function modificar(): void
    {
        $pdo = Empleado::pdo();
        $sent = $pdo->prepare('UPDATE empleados
                                  SET
                                    numero = :numero,
                                    nombre = :nombre,
                                    apellidos = :apellidos,
                                    fechanac = :fechanac,
                                    codpostal = :codpostal,
                                    telefono = :telefono,
                                    departamento_id = :departamento_id
                                WHERE id = :id');
        $sent->execute([
            ':id' => $this->id,
            ':numero' => $this->numero,
            ':nombre' => $this->nombre,
            ':apellidos' => $this->apellidos,
            ':fechanac' => $this->fechanac,
            ':codpostal' => $this->codpostal,
            ':telefono' => $this->telefono,
            ':departamento_id' => $this->departamento_id,
        ]);
    }
}
