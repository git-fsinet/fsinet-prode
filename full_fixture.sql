-- Official Fixture 2026 World Cup FSInet (104 Matches)
USE prode_mundial;

-- Clean existing matches
DELETE FROM matches;
ALTER TABLE matches AUTO_INCREMENT = 1;

-- Clean and Reset Stages
DELETE FROM stages;
ALTER TABLE stages AUTO_INCREMENT = 1;

INSERT INTO stages (id, name, is_open, display_order) VALUES 
(1, 'Fase de Grupos', 1, 1),
(2, 'Dieciseisavos de Final', 0, 2),
(3, 'Octavos de Final', 0, 3),
(4, 'Cuartos de Final', 0, 4),
(5, 'Semifinales', 0, 5),
(6, 'Tercer Puesto', 0, 6),
(7, 'Final', 0, 7);

-- FASE DE GRUPOS (72 Partidos)

-- GRUPO A: México, Sudáfrica, Corea del Sur, Chequia
INSERT INTO matches (team1, team2, stage_id, group_name, matchday, stadium, match_date) VALUES 
('México', 'Sudáfrica', 1, 'A', 1, 'Estadio Azteca, CDMX', '2026-06-11 20:00:00'),
('Corea del Sur', 'Chequia', 1, 'A', 1, 'Ciudad de México', '2026-06-11 23:00:00'),
('México', 'Corea del Sur', 1, 'A', 2, 'Estadio BBVA, Monterrey', '2026-06-16 18:00:00'),
('Chequia', 'Sudáfrica', 1, 'A', 2, 'Estadio Azteca, CDMX', '2026-06-17 21:00:00'),
('México', 'Chequia', 1, 'A', 3, 'Estadio Azteca, CDMX', '2026-06-24 16:00:00'),
('Sudáfrica', 'Corea del Sur', 1, 'A', 3, 'Estadio Akron, Guadalajara', '2026-06-24 16:00:00');

-- GRUPO B: Canadá, Bosnia y Herzegovina, Catar, Suiza
INSERT INTO matches (team1, team2, stage_id, group_name, matchday, stadium, match_date) VALUES 
('Canadá', 'Bosnia y Herzegovina', 1, 'B', 1, 'BMO Field, Toronto', '2026-06-12 18:00:00'),
('Catar', 'Suiza', 1, 'B', 1, 'BC Place, Vancouver', '2026-06-13 14:00:00'),
('Canadá', 'Catar', 1, 'B', 2, 'BMO Field, Toronto', '2026-06-18 19:00:00'),
('Suiza', 'Bosnia y Herzegovina', 1, 'B', 2, 'BC Place, Vancouver', '2026-06-19 16:00:00'),
('Canadá', 'Suiza', 1, 'B', 3, 'BMO Field, Toronto', '2026-06-24 19:00:00'),
('Bosnia y Herzegovina', 'Catar', 1, 'B', 3, 'BC Place, Vancouver', '2026-06-24 19:00:00');

-- GRUPO C: Brasil, Marruecos, Haití, Escocia
INSERT INTO matches (team1, team2, stage_id, group_name, matchday, stadium, match_date) VALUES 
('Brasil', 'Marruecos', 1, 'C', 1, 'Hard Rock Stadium, Miami', '2026-06-13 19:00:00'),
('Haití', 'Escocia', 1, 'C', 1, 'Mercedes-Benz Stadium, Atlanta', '2026-06-14 15:00:00'),
('Brasil', 'Haití', 1, 'C', 2, 'Hard Rock Stadium, Miami', '2026-06-19 21:00:00'),
('Escocia', 'Marruecos', 1, 'C', 2, 'Mercedes-Benz Stadium, Atlanta', '2026-06-20 18:00:00'),
('Brasil', 'Escocia', 1, 'C', 3, 'Hard Rock Stadium, Miami', '2026-06-25 18:00:00'),
('Marruecos', 'Haití', 1, 'C', 3, 'Mercedes-Benz Stadium, Atlanta', '2026-06-25 18:00:00');

-- GRUPO D: Estados Unidos, Paraguay, Australia, Turquía
INSERT INTO matches (team1, team2, stage_id, group_name, matchday, stadium, match_date) VALUES 
('Estados Unidos', 'Paraguay', 1, 'D', 1, 'SoFi Stadium, Los Angeles', '2026-06-12 21:00:00'),
('Australia', 'Turquía', 1, 'D', 1, 'Levi\'s Stadium, San Francisco', '2026-06-13 17:00:00'),
('Estados Unidos', 'Australia', 1, 'D', 2, 'Lumen Field, Seattle', '2026-06-19 21:00:00'),
('Turquía', 'Paraguay', 1, 'D', 2, 'SoFi Stadium, Los Angeles', '2026-06-20 18:00:00'),
('Estados Unidos', 'Turquía', 1, 'D', 3, 'SoFi Stadium, Los Angeles', '2026-06-25 18:00:00'),
('Paraguay', 'Australia', 1, 'D', 3, 'Lumen Field, Seattle', '2026-06-25 18:00:00');

-- GRUPO E: Alemania, Curazao, Costa de Marfil, Ecuador
INSERT INTO matches (team1, team2, stage_id, group_name, matchday, stadium, match_date) VALUES 
('Alemania', 'Curazao', 1, 'E', 1, 'MetLife Stadium, New Jersey', '2026-06-14 16:00:00'),
('Costa de Marfil', 'Ecuador', 1, 'E', 1, 'Lincoln Financial Field, Philadelphia', '2026-06-15 13:00:00'),
('Alemania', 'Costa de Marfil', 1, 'E', 2, 'Gillette Stadium, Boston', '2026-06-20 19:00:00'),
('Ecuador', 'Curazao', 1, 'E', 2, 'MetLife Stadium, New Jersey', '2026-06-21 16:00:00'),
('Alemania', 'Ecuador', 1, 'E', 3, 'Lincoln Financial Field, Philadelphia', '2026-06-26 21:00:00'),
('Curazao', 'Costa de Marfil', 1, 'E', 3, 'Gillette Stadium, Boston', '2026-06-26 21:00:00');

-- GRUPO F: Países Bajos, Japón, Suecia, Túnez
INSERT INTO matches (team1, team2, stage_id, group_name, matchday, stadium, match_date) VALUES 
('Países Bajos', 'Japón', 1, 'F', 1, 'NRG Stadium, Houston', '2026-06-16 21:00:00'),
('Suecia', 'Túnez', 1, 'F', 1, 'AT&T Stadium, Dallas', '2026-06-17 18:00:00'),
('Países Bajos', 'Suecia', 1, 'F', 2, 'NRG Stadium, Houston', '2026-06-22 21:00:00'),
('Túnez', 'Japón', 1, 'F', 2, 'AT&T Stadium, Dallas', '2026-06-23 18:00:00'),
('Países Bajos', 'Túnez', 1, 'F', 3, 'NRG Stadium, Houston', '2026-06-28 15:00:00'),
('Japón', 'Suecia', 1, 'F', 3, 'AT&T Stadium, Dallas', '2026-06-28 15:00:00');

-- GRUPO G: Bélgica, Egipto, Irán, Nueva Zelanda
INSERT INTO matches (team1, team2, stage_id, group_name, matchday, stadium, match_date) VALUES 
('Bélgica', 'Egipto', 1, 'G', 1, 'Arrowhead Stadium, Kansas City', '2026-06-17 21:00:00'),
('Irán', 'Nueva Zelanda', 1, 'G', 1, 'SoFi Stadium, Los Angeles', '2026-06-18 18:00:00'),
('Bélgica', 'Irán', 1, 'G', 2, 'Arrowhead Stadium, Kansas City', '2026-06-23 21:00:00'),
('Nueva Zelanda', 'Egipto', 1, 'G', 2, 'SoFi Stadium, Los Angeles', '2026-06-24 18:00:00'),
('Bélgica', 'Nueva Zelanda', 1, 'G', 3, 'Arrowhead Stadium, Kansas City', '2026-06-29 20:00:00'),
('Egipto', 'Irán', 1, 'G', 3, 'SoFi Stadium, Los Angeles', '2026-06-29 20:00:00');

-- GRUPO H: España, Cabo Verde, Arabia Saudita, Uruguay
INSERT INTO matches (team1, team2, stage_id, group_name, matchday, stadium, match_date) VALUES 
('España', 'Cabo Verde', 1, 'H', 1, 'MetLife Stadium, New Jersey', '2026-06-18 21:00:00'),
('Arabia Saudita', 'Uruguay', 1, 'H', 1, 'Lincoln Financial Field, Philadelphia', '2026-06-19 18:00:00'),
('España', 'Arabia Saudita', 1, 'H', 2, 'Gillette Stadium, Boston', '2026-06-24 21:00:00'),
('Uruguay', 'Cabo Verde', 1, 'H', 2, 'MetLife Stadium, New Jersey', '2026-06-25 18:00:00'),
('España', 'Uruguay', 1, 'H', 3, 'Lincoln Financial Field, Philadelphia', '2026-06-30 19:00:00'),
('Cabo Verde', 'Arabia Saudita', 1, 'H', 3, 'Gillette Stadium, Boston', '2026-06-30 19:00:00');

-- GRUPO I: Francia, Senegal, Irak, Noruega
INSERT INTO matches (team1, team2, stage_id, group_name, matchday, stadium, match_date) VALUES 
('Francia', 'Senegal', 1, 'I', 1, 'AT&T Stadium, Dallas', '2026-06-20 15:00:00'),
('Irak', 'Noruega', 1, 'I', 1, 'NRG Stadium, Houston', '2026-06-20 18:00:00'),
('Francia', 'Irak', 1, 'I', 2, 'AT&T Stadium, Dallas', '2026-06-25 15:00:00'),
('Noruega', 'Senegal', 1, 'I', 2, 'NRG Stadium, Houston', '2026-06-25 18:00:00'),
('Francia', 'Noruega', 1, 'I', 3, 'AT&T Stadium, Dallas', '2026-06-30 21:00:00'),
('Senegal', 'Irak', 1, 'I', 3, 'NRG Stadium, Houston', '2026-06-30 21:00:00');

-- GRUPO J: Argentina, Argelia, Austria, Jordania
INSERT INTO matches (team1, team2, stage_id, group_name, matchday, stadium, match_date) VALUES 
('Argentina', 'Argelia', 1, 'J', 1, 'MetLife Stadium, New Jersey', '2026-06-16 16:00:00'),
('Austria', 'Jordania', 1, 'J', 1, 'Hard Rock Stadium, Miami', '2026-06-16 19:00:00'),
('Argentina', 'Austria', 1, 'J', 2, 'Mercedes-Benz Stadium, Atlanta', '2026-06-21 21:00:00'),
('Jordania', 'Argelia', 1, 'J', 2, 'Hard Rock Stadium, Miami', '2026-06-22 18:00:00'),
('Argentina', 'Jordania', 1, 'J', 3, 'Mercedes-Benz Stadium, Atlanta', '2026-06-27 18:00:00'),
('Argelia', 'Austria', 1, 'J', 3, 'Hard Rock Stadium, Miami', '2026-06-27 18:00:00');

-- GRUPO K: Portugal, RD Congo, Uzbekistán, Colombia
INSERT INTO matches (team1, team2, stage_id, group_name, matchday, stadium, match_date) VALUES 
('Portugal', 'RD Congo', 1, 'K', 1, 'Arrowhead Stadium, Kansas City', '2026-06-22 15:00:00'),
('Uzbekistán', 'Colombia', 1, 'K', 1, 'SoFi Stadium, Los Angeles', '2026-06-22 18:00:00'),
('Portugal', 'Uzbekistán', 1, 'K', 2, 'Arrowhead Stadium, Kansas City', '2026-06-27 15:00:00'),
('Colombia', 'RD Congo', 1, 'K', 2, 'SoFi Stadium, Los Angeles', '2026-06-27 18:00:00'),
('Portugal', 'Colombia', 1, 'K', 3, 'Arrowhead Stadium, Kansas City', '2026-07-02 18:00:00'),
('RD Congo', 'Uzbekistán', 1, 'K', 3, 'SoFi Stadium, Los Angeles', '2026-07-02 18:00:00');

-- GRUPO L: Inglaterra, Croacia, Ghana, Panamá
INSERT INTO matches (team1, team2, stage_id, group_name, matchday, stadium, match_date) VALUES 
('Inglaterra', 'Croacia', 1, 'L', 1, 'MetLife Stadium, New Jersey', '2026-06-23 15:00:00'),
('Ghana', 'Panamá', 1, 'L', 1, 'Lincoln Financial Field, Philadelphia', '2026-06-23 18:00:00'),
('Inglaterra', 'Ghana', 1, 'L', 2, 'Gillette Stadium, Boston', '2026-06-28 15:00:00'),
('Panamá', 'Croacia', 1, 'L', 2, 'MetLife Stadium, New Jersey', '2026-06-28 18:00:00'),
('Inglaterra', 'Panamá', 1, 'L', 3, 'Lincoln Financial Field, Philadelphia', '2026-07-03 15:00:00'),
('Croacia', 'Ghana', 1, 'L', 3, 'Gillette Stadium, Boston', '2026-07-03 15:00:00');

-- DIECISEISAVOS DE FINAL (16 Partidos)
INSERT INTO matches (team1, team2, stage_id, stadium, match_date) VALUES 
('1º Grupo A', '2º Grupo C', 2, 'Estadio Azteca, CDMX', '2026-07-04 15:00:00'),
('1º Grupo B', '2º Grupo D', 2, 'BMO Field, Toronto', '2026-07-04 18:00:00'),
('1º Grupo E', '3º Grupo G/H/I', 2, 'SoFi Stadium, Los Angeles', '2026-07-05 15:00:00'),
('1º Grupo F', '2º Grupo H', 2, 'MetLife Stadium, New Jersey', '2026-07-05 18:00:00'),
('1º Grupo C', '2º Grupo A', 2, 'Mercedes-Benz Stadium, Atlanta', '2026-07-06 15:00:00'),
('1º Grupo D', '2º Grupo B', 2, 'Hard Rock Stadium, Miami', '2026-07-06 18:00:00'),
('1º Grupo G', '3º Grupo I/J/K', 2, 'NRG Stadium, Houston', '2026-07-07 15:00:00'),
('1º Grupo H', '2º Grupo F', 2, 'Arrowhead Stadium, Kansas City', '2026-07-07 18:00:00'),
('1º Grupo I', '3º Grupo A/B/C', 2, 'Gillette Stadium, Boston', '2026-07-08 15:00:00'),
('1º Grupo J', '2º Grupo L', 2, 'BC Place, Vancouver', '2026-07-08 18:00:00'),
('1º Grupo K', '3º Grupo D/E/F', 2, 'Lumen Field, Seattle', '2026-07-09 15:00:00'),
('1º Grupo L', '2º Grupo J', 2, 'Levi\'s Stadium, San Francisco', '2026-07-09 18:00:00'),
('2º Grupo E', '2º Grupo G', 2, 'Lincoln Financial Field, Philadelphia', '2026-07-10 15:00:00'),
('2º Grupo I', '2º Grupo K', 2, 'AT&T Stadium, Dallas', '2026-07-10 18:00:00'),
('3º Grupo B/E/F', '1º Grupo M', 2, 'SoFi Stadium, Los Angeles', '2026-07-11 15:00:00'),
('3º Grupo C/D/L', '2º Grupo N', 2, 'Estadio Azteca, CDMX', '2026-07-11 18:00:00');

-- OCTAVOS DE FINAL (8 Partidos)
INSERT INTO matches (team1, team2, stage_id, stadium, match_date) VALUES 
('Ganador 73', 'Ganador 74', 3, 'Estadio Azteca, CDMX', '2026-07-13 20:00:00'),
('Ganador 75', 'Ganador 76', 3, 'MetLife Stadium, New Jersey', '2026-07-13 20:00:00'),
('Ganador 77', 'Ganador 78', 3, 'SoFi Stadium, Los Angeles', '2026-07-14 20:00:00'),
('Ganador 79', 'Ganador 80', 3, 'Hard Rock Stadium, Miami', '2026-07-14 20:00:00'),
('Ganador 81', 'Ganador 82', 3, 'Mercedes-Benz Stadium, Atlanta', '2026-07-15 20:00:00'),
('Ganador 83', 'Ganador 84', 3, 'NRG Stadium, Houston', '2026-07-15 20:00:00'),
('Ganador 85', 'Ganador 86', 3, 'Arrowhead Stadium, Kansas City', '2026-07-16 20:00:00'),
('Ganador 87', 'Ganador 88', 3, 'BC Place, Vancouver', '2026-07-16 20:00:00');

-- CUARTOS DE FINAL (4 Partidos)
INSERT INTO matches (team1, team2, stage_id, stadium, match_date) VALUES 
('Ganador Octavos 1', 'Ganador Octavos 2', 4, 'SoFi Stadium, Los Angeles', '2026-07-18 20:00:00'),
('Ganador Octavos 3', 'Ganador Octavos 4', 4, 'Hard Rock Stadium, Miami', '2026-07-18 20:00:00'),
('Ganador Octavos 5', 'Ganador Octavos 6', 4, 'Arrowhead Stadium, Kansas City', '2026-07-19 20:00:00'),
('Ganador Octavos 7', 'Ganador Octavos 8', 4, 'Gillette Stadium, Boston', '2026-07-19 20:00:00');

-- SEMIFINALES (2 Partidos)
INSERT INTO matches (team1, team2, stage_id, stadium, match_date) VALUES 
('Ganador Cuartos 1', 'Ganador Cuartos 2', 5, 'AT&T Stadium, Dallas', '2026-07-22 21:00:00'),
('Ganador Cuartos 3', 'Ganador Cuartos 4', 5, 'Mercedes-Benz Stadium, Atlanta', '2026-07-23 21:00:00');

-- TERCER PUESTO (1 Partido)
INSERT INTO matches (team1, team2, stage_id, stadium, match_date) VALUES 
('Perdedor Semi 1', 'Perdedor Semi 2', 6, 'Hard Rock Stadium, Miami', '2026-07-25 20:00:00');

-- FINAL (1 Partido)
INSERT INTO matches (team1, team2, stage_id, stadium, match_date) VALUES 
('Ganador Semi 1', 'Ganador Semi 2', 7, 'MetLife Stadium, New Jersey', '2026-07-26 16:00:00');
