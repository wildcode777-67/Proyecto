CREATE DATABASE IF NOT EXISTS sigsm_practico
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_spanish_ci;

USE sigsm_practico;

CREATE TABLE documento (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  titulo           VARCHAR(150) NOT NULL,
  tipo             ENUM('indicacion', 'informacion') NOT NULL,
  cedula_paciente  VARCHAR(8) NOT NULL,
  fecha_emision    DATE NOT NULL,
  ruta_archivo     VARCHAR(255) NOT NULL UNIQUE,
  activo           TINYINT(1) NOT NULL DEFAULT 1
);

INSERT INTO documento (titulo, tipo, cedula_paciente, fecha_emision, ruta_archivo) VALUES
('Dieta para examen de sangre', 'indicacion', '45678912', '2026-08-10', 'documentos/indicacion_45678912_001.pdf'),
('Horarios de visita sala 3', 'informacion', '39871234', '2026-08-11', 'documentos/informacion_39871234_001.pdf'),
('Preparación para ecografía abdominal', 'indicacion', '40123456', '2026-08-12', 'documentos/indicacion_40123456_001.pdf'),
('Normas de internación pediátrica', 'informacion', '45678912', '2026-08-13', 'documentos/informacion_45678912_002.pdf');
