

CREATE DATABASE IF NOT EXISTS sigsm
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_spanish_ci;

USE sigsm;


CREATE TABLE usuario (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  nombre       VARCHAR(100) NOT NULL,
  email        VARCHAR(120) NOT NULL UNIQUE,
  contrasenia  VARCHAR(255) NOT NULL,        -- se guarda el HASH, nunca el texto
  rol          VARCHAR(20)  NOT NULL DEFAULT 'medico',  -- 'administrativo' o 'medico'
  activo       TINYINT(1)   DEFAULT 1
);


CREATE TABLE categoria (
  id_categoria INT AUTO_INCREMENT PRIMARY KEY,
  nombre       VARCHAR(80) NOT NULL UNIQUE
);

CREATE TABLE documento (
  id_documento INT AUTO_INCREMENT PRIMARY KEY,
  titulo       VARCHAR(150) NOT NULL,
  descripcion  TEXT,
  id_categoria INT NOT NULL,
  id_usuario   INT NOT NULL,
  fecha_subida DATE NOT NULL DEFAULT (CURRENT_DATE),
  ruta_archivo VARCHAR(255),
  codigo_qr    VARCHAR(255),

  CONSTRAINT fk_documento_categoria
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria),

  CONSTRAINT fk_documento_usuario
    FOREIGN KEY (id_usuario) REFERENCES usuario(id)
);


CREATE TABLE encuesta (
  id_encuesta  INT AUTO_INCREMENT PRIMARY KEY,
  id_documento INT NOT NULL,
  calificacion TINYINT NOT NULL CHECK (calificacion BETWEEN 1 AND 5),
  comentario   TEXT,
  fecha        DATE NOT NULL DEFAULT (CURRENT_DATE),

  CONSTRAINT fk_encuesta_documento
    FOREIGN KEY (id_documento) REFERENCES documento(id_documento)
);
