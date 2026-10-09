DROP TABLE IF EXISTS departamentos CASCADE;

CREATE TABLE departamentos (
    id           BIGSERIAL    PRIMARY KEY,
    codigo       NUMERIC(2)   NOT NULL UNIQUE CHECK (codigo >= 0),
    denominacion VARCHAR(255) NOT NULL
);

DROP TABLE IF EXISTS empleados CASCADE;

CREATE TABLE empleados (
    id              BIGSERIAL     PRIMARY KEY,
    numero          NUMERIC(4)    NOT NULL UNIQUE CHECK (numero >= 0),
    dni             VARCHAR(9)    NOT NULL,
    nombre          VARCHAR(255)  NOT NULL,
    apellidos       VARCHAR(255),
    codpostal       NUMERIC(5)    NOT NULL CHECK (codpostal >= 0),
    telefono        NUMERIC(14),
    departamento_id BIGINT        REFERENCES departamentos (id)
);

-- Datos de prueba:

INSERT INTO departamentos (codigo, denominacion)
VALUES (10, 'Informática'),
       (20, 'Administrativo'),
       (30, 'Prevención'),
       (40, 'Laboratorio');
