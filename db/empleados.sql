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
    nombre          VARCHAR(255)  NOT NULL,
    apellidos       VARCHAR(255),
    fechanac        DATE          NOT NULL,
    codpostal       NUMERIC(5)    NOT NULL CHECK (codpostal >= 0),
    telefono        NUMERIC(14),
    departamento_id BIGINT        NOT NULL REFERENCES departamentos (id)
);

DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios (
    id       BIGSERIAL    PRIMARY KEY,
    email    VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Datos de prueba:

INSERT INTO departamentos (codigo, denominacion)
VALUES (10, 'Informática'),
       (20, 'Administrativo'),
       (30, 'Prevención'),
       (40, 'Laboratorio');

INSERT INTO empleados (numero, nombre, apellidos, fechanac, codpostal, telefono, departamento_id)
VALUES (1000, 'Antonio', 'González', '19801004'::date, 11540, NULL, 2),
       (2000, 'María', 'Pérez', '20020501'::date, 11550, 666555444, 4);

INSERT INTO usuarios (email, password)
VALUES ('admin@admin.com', crypt('admin', gen_salt('bf', 12)));
