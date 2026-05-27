-- Actualización de la Base de Datos para el Prode FSInet
-- Ejecutar esto en el servidor de producción después de subir los archivos por FTP/Git

-- 1. Agregar columnas de penales a la tabla de partidos
ALTER TABLE matches 
ADD COLUMN IF NOT EXISTS penalties1 int DEFAULT NULL,
ADD COLUMN IF NOT EXISTS penalties2 int DEFAULT NULL;

-- 2. Agregar columnas de ganador de penales a la tabla de pronósticos
ALTER TABLE predictions 
ADD COLUMN IF NOT EXISTS penalty_winner_team1 tinyint(1) DEFAULT '0',
ADD COLUMN IF NOT EXISTS penalty_winner_team2 tinyint(1) DEFAULT '0';

-- (Opcional) Si quieres limpiar la base de datos de producción igual que hicimos en local
-- para que empiece de cero, descomenta y ejecuta las siguientes líneas:

-- DELETE FROM predictions;
-- ALTER TABLE predictions AUTO_INCREMENT = 1;
-- DELETE FROM users WHERE is_admin = 0;
-- UPDATE users SET points = 0 WHERE is_admin = 1;
