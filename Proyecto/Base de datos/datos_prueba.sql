

USE sigsm;

-- ------------------------------------------------------------
-- usuario
-- ------------------------------------------------------------
INSERT INTO usuario (nombre, email, contrasenia, rol, activo) VALUES
('Luis Fagúndez', 'luis.fagundez@sigsm.uy', '$2y$10$CuCmcDlk4FiFFzGI5VDvbOhUbTb19rCGY3vUMyf.Mb4VT7qGHbL66', 'administrativo', 1),
('Gabriel Barboza', 'gabriel.barboza@sigsm.uy', '$2y$10$CuCmcDlk4FiFFzGI5VDvbOhUbTb19rCGY3vUMyf.Mb4VT7qGHbL66', 'administrativo', 1),
('María Rodríguez', 'maria.rodriguez@sigsm.uy', '$2y$10$CuCmcDlk4FiFFzGI5VDvbOhUbTb19rCGY3vUMyf.Mb4VT7qGHbL66', 'medico', 1),
('Carlos Pérez', 'carlos.perez@sigsm.uy', '$2y$10$CuCmcDlk4FiFFzGI5VDvbOhUbTb19rCGY3vUMyf.Mb4VT7qGHbL66', 'medico', 1);

-- ------------------------------------------------------------
-- categoria
-- ------------------------------------------------------------
INSERT INTO categoria (nombre) VALUES
('Nefrología'),
('Cardiología'),
('Trasplantes'),
('Estudios imagenológicos'),
('Pacientes ostomizados');

-- ------------------------------------------------------------
-- documento
-- ------------------------------------------------------------
INSERT INTO documento (titulo, descripcion, id_categoria, id_usuario, fecha_subida, ruta_archivo, codigo_qr) VALUES
('Indicaciones para pacientes en tratamiento con warfarina', 'Recomendaciones de cuidado y controles para pacientes anticoagulados.', 1, 1, '2026-08-10', 'documentos/warfarina.pdf', 'QR-DOC-001'),
('Plan de alta enfermería, Nefrología', 'Instrucciones de enfermería para el alta de pacientes de nefrología.', 1, 1, '2026-08-05', 'documentos/alta-nefrologia.pdf', 'QR-DOC-002'),
('Indicaciones ecocardiograma con dobutamina', 'Preparación previa al estudio de ecocardiograma con dobutamina.', 2, 2, '2026-08-08', 'documentos/ecocardiograma-dobutamina.pdf', 'QR-DOC-003'),
('Indicaciones de enfermería para usuarios trasplantados', 'Cuidados post trasplante indicados por el equipo de enfermería.', 3, 2, '2026-08-01', 'documentos/trasplante-enfermeria.pdf', 'QR-DOC-004'),
('Preparación para estudios imagenológicos', 'Requisitos y ayuno previo a estudios de imagen.', 4, 1, '2026-07-28', 'documentos/prep-imagenologia.pdf', 'QR-DOC-005'),
('Pauta para pacientes ostomizados', 'Cuidado de la ostomía en el hogar.', 5, 2, '2026-07-20', 'documentos/pauta-ostomizados.pdf', 'QR-DOC-006');

-- ------------------------------------------------------------
-- encuesta
-- Anónima: no lleva id_usuario ni id_paciente
-- ------------------------------------------------------------
INSERT INTO encuesta (id_documento, calificacion, comentario, fecha) VALUES
(1, 5, 'Muy claro y fácil de entender.', '2026-08-11'),
(1, 4, 'Buena información, un poco larga.', '2026-08-12'),
(3, 5, 'Me ayudó mucho antes del estudio.', '2026-08-09'),
(4, 3, 'Faltaría más detalle sobre la dieta.', '2026-08-02'),
(6, 5, NULL, '2026-07-21');
