-- ============================================================
-- PROJET : QUIZ DE CULTURE GENERALE GAMIFIE
-- FICHIER : donnees_test.sql
-- DESCRIPTION : Donnees initiales pour tester l'application
-- ============================================================

USE quiz_culture_generale;

-- ============================================================
-- 1. INSERTION DES CATEGORIES
-- ============================================================

INSERT INTO categories (id_categorie, nom_categorie, description)
VALUES
(1, 'Géographie', 'Pays, capitales et continents'),
(2, 'Histoire', 'Événements et personnages historiques'),
(3, 'Sciences', 'Sciences et découvertes'),
(4, 'Sport', 'Sports et compétitions'),
(5, 'Culture générale', 'Connaissances variées');


-- ============================================================
-- 2. INSERTION DES DIFFICULTES
-- ============================================================

INSERT INTO difficultes (id_difficulte, libelle, multiplicateur)
VALUES
(1, 'Facile', 1.00),
(2, 'Intermédiaire', 1.50),
(3, 'Difficile', 2.00),
(4, 'Expert', 3.00),
(5, 'Master', 5.00);


-- ============================================================
-- 3. INSERTION DES QUESTIONS
-- ============================================================

INSERT INTO questions (
    id_question,
    enonce,
    explication,
    id_categorie,
    id_difficulte,
    statut_validation
)
VALUES

-- GEOGRAPHIE

(1,
 'Quelle est la capitale du Togo ?',
 'Lomé est la capitale du Togo.',
 1, 1, 'validee'),

(2,
 'Quel est le plus grand océan du monde ?',
 'L’océan Pacifique est le plus grand océan du monde.',
 1, 2, 'validee'),

-- HISTOIRE

(3,
 'En quelle année a commencé la Première Guerre mondiale ?',
 'La Première Guerre mondiale a commencé en 1914.',
 2, 1, 'validee'),

(4,
 'Qui était le premier président de la République française ?',
 'Louis-Napoléon Bonaparte fut le premier président de la République française.',
 2, 3, 'validee'),

-- SCIENCES

(5,
 'Quelle planète est surnommée la planète rouge ?',
 'Mars est surnommée la planète rouge.',
 3, 1, 'validee'),

(6,
 'Quel est le symbole chimique de l’or ?',
 'Le symbole chimique de l’or est Au.',
 3, 2, 'validee'),

-- SPORT

(7,
 'Combien de joueurs composent une équipe de football sur le terrain ?',
 'Une équipe de football compte 11 joueurs sur le terrain.',
 4, 1, 'validee'),

(8,
 'Quel pays a remporté la Coupe du monde de football en 2018 ?',
 'La France a remporté la Coupe du monde de football en 2018.',
 4, 2, 'validee'),

-- CULTURE GENERALE

(9,
 'Qui a peint La Joconde ?',
 'Léonard de Vinci est le peintre de La Joconde.',
 5, 1, 'validee'),

(10,
 'Quel écrivain français a écrit Les Misérables ?',
 'Victor Hugo est l’auteur du roman Les Misérables.',
 5, 2, 'validee');


-- ============================================================
-- 4. INSERTION DES REPONSES
-- ============================================================

INSERT INTO reponses (
    id_question,
    texte_reponse,
    est_correcte
)
VALUES

-- QUESTION 1 : CAPITALE DU TOGO
(1, 'Cotonou', FALSE),
(1, 'Lomé', TRUE),
(1, 'Accra', FALSE),
(1, 'Dakar', FALSE),

-- QUESTION 2 : PLUS GRAND OCEAN
(2, 'Océan Atlantique', FALSE),
(2, 'Océan Indien', FALSE),
(2, 'Océan Pacifique', TRUE),
(2, 'Océan Arctique', FALSE),

-- QUESTION 3 : PREMIERE GUERRE MONDIALE
(3, '1912', FALSE),
(3, '1914', TRUE),
(3, '1918', FALSE),
(3, '1939', FALSE),

-- QUESTION 4 : PREMIER PRESIDENT FRANCAIS
(4, 'Charles de Gaulle', FALSE),
(4, 'Louis-Napoléon Bonaparte', TRUE),
(4, 'Adolphe Thiers', FALSE),
(4, 'Jules Grévy', FALSE),

-- QUESTION 5 : PLANETE ROUGE
(5, 'Vénus', FALSE),
(5, 'Mars', TRUE),
(5, 'Jupiter', FALSE),
(5, 'Saturne', FALSE),

-- QUESTION 6 : SYMBOLE CHIMIQUE DE L'OR
(6, 'Ag', FALSE),
(6, 'Au', TRUE),
(6, 'Fe', FALSE),
(6, 'Or', FALSE),

-- QUESTION 7 : FOOTBALL
(7, '9 joueurs', FALSE),
(7, '10 joueurs', FALSE),
(7, '11 joueurs', TRUE),
(7, '12 joueurs', FALSE),

-- QUESTION 8 : COUPE DU MONDE 2018
(8, 'Brésil', FALSE),
(8, 'Allemagne', FALSE),
(8, 'France', TRUE),
(8, 'Argentine', FALSE),

-- QUESTION 9 : LA JOCONDE
(9, 'Pablo Picasso', FALSE),
(9, 'Vincent van Gogh', FALSE),
(9, 'Léonard de Vinci', TRUE),
(9, 'Claude Monet', FALSE),

-- QUESTION 10 : LES MISERABLES
(10, 'Émile Zola', FALSE),
(10, 'Victor Hugo', TRUE),
(10, 'Albert Camus', FALSE),
(10, 'Molière', FALSE);


-- ============================================================
-- 5. VERIFICATION DES DONNEES
-- ============================================================

SELECT 'Categories' AS element, COUNT(*) AS total
FROM categories

UNION ALL

SELECT 'Difficultes', COUNT(*)
FROM difficultes

UNION ALL

SELECT 'Questions', COUNT(*)
FROM questions

UNION ALL

SELECT 'Reponses', COUNT(*)
FROM reponses;


-- ============================================================
-- FIN DU FICHIER donnees_test.sql
-- ============================================================