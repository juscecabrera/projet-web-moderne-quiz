-- Questions complémentaires pour Géographie et Sciences. Réimportable.
USE quiz_culture_generale;
CREATE TEMPORARY TABLE questions_niveaux (
    theme_id INT NOT NULL,
    niveau INT NOT NULL,
    enonce VARCHAR(255) NOT NULL PRIMARY KEY,
    bonne VARCHAR(255) NOT NULL,
    choix_b VARCHAR(255) NOT NULL,
    choix_c VARCHAR(255) NOT NULL,
    choix_d VARCHAR(255) NOT NULL,
    explication VARCHAR(400) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

INSERT INTO questions_niveaux VALUES
(1, 1, 'Dans quel pays se trouve la ville de Porto ?', 'Portugal', 'Espagne', 'Italie', 'Grèce', 'Porto se trouve dans le nord du Portugal, près de l’embouchure du Douro.'),
(1, 1, 'Quel continent abrite le désert du Sahara ?', 'Afrique', 'Asie', 'Amérique du Sud', 'Océanie', 'Le Sahara s’étend sur plusieurs pays du nord de l’Afrique.'),
(1, 1, 'Quelle mer borde le sud de la France métropolitaine ?', 'Mer Méditerranée', 'Mer Baltique', 'Mer du Nord', 'Mer Rouge', 'Le littoral du sud-est de la France borde la Méditerranée.'),
(1, 1, 'Dans quel pays se trouve la ville de Marrakech ?', 'Maroc', 'Algérie', 'Tunisie', 'Égypte', 'Marrakech se situe au Maroc, au pied du Haut Atlas.'),
(1, 1, 'Quelle capitale est traversée par la Seine ?', 'Paris', 'Madrid', 'Rome', 'Vienne', 'La Seine traverse Paris puis rejoint la Manche.'),
(1, 2, 'Quel fleuve traverse Budapest ?', 'Danube', 'Rhin', 'Elbe', 'Vistule', 'Le Danube sépare historiquement Buda et Pest.'),
(1, 2, 'Quel pays a pour capitale Oulan-Bator ?', 'Mongolie', 'Kazakhstan', 'Kirghizistan', 'Bhoutan', 'Oulan-Bator est la capitale de la Mongolie.'),
(1, 2, 'Quel détroit sépare le sud de l’Espagne du nord du Maroc ?', 'Gibraltar', 'Béring', 'Bosphore', 'Ormuz', 'Le détroit de Gibraltar relie l’Atlantique à la Méditerranée.'),
(1, 2, 'Dans quel pays se trouve le lac de Bled ?', 'Slovénie', 'Slovaquie', 'Croatie', 'Autriche', 'Bled se situe dans les Alpes juliennes, en Slovénie.'),
(1, 2, 'Quelle chaîne de montagnes longe une grande partie de l’ouest de l’Amérique du Sud ?', 'Andes', 'Rocheuses', 'Atlas', 'Oural', 'Les Andes s’étendent notamment du Venezuela au sud du Chili et de l’Argentine.'),
(1, 3, 'Quelle est la capitale administrative des Palaos ?', 'Ngerulmud', 'Koror', 'Majuro', 'Palikir', 'Ngerulmud a remplacé Koror comme capitale en 2006.'),
(1, 3, 'Quel pays administre l’archipel des Svalbard ?', 'Norvège', 'Danemark', 'Islande', 'Finlande', 'Le traité du Svalbard reconnaît la souveraineté de la Norvège sur l’archipel.'),
(1, 3, 'Quel détroit relie la mer Rouge au golfe d’Aden ?', 'Bab-el-Mandeb', 'Ormuz', 'Malacca', 'Messine', 'Bab-el-Mandeb se situe entre le Yémen et la Corne de l’Afrique.'),
(1, 3, 'Quel lac reçoit les eaux du fleuve Omo ?', 'Lac Turkana', 'Lac Kivu', 'Lac Malawi', 'Lac Tchad', 'L’Omo coule en Éthiopie et se jette dans le lac Turkana.'),
(1, 3, 'Quel pays est entièrement enclavé dans l’Afrique du Sud ?', 'Lesotho', 'Eswatini', 'Botswana', 'Namibie', 'Le territoire du Lesotho est entièrement entouré par l’Afrique du Sud.'),
(1, 3, 'Quel est le plus grand lac entièrement situé au Canada ?', 'Grand lac de l’Ours', 'Grand lac des Esclaves', 'Lac Winnipeg', 'Lac Athabasca', 'Le Grand lac de l’Ours se situe dans les Territoires du Nord-Ouest.'),
(3, 1, 'Quel organe pompe le sang dans le corps humain ?', 'Le cœur', 'Le foie', 'Le poumon', 'L’estomac', 'Le cœur propulse le sang dans les vaisseaux.'),
(3, 1, 'Quel état prend l’eau lorsqu’elle devient de la glace ?', 'Solide', 'Liquide', 'Gazeux', 'Plasma', 'La glace est la forme solide de l’eau.'),
(3, 1, 'Combien de pattes a une araignée adulte ?', 'Huit', 'Six', 'Dix', 'Douze', 'Les araignées sont des arachnides à quatre paires de pattes.'),
(3, 1, 'Quel objet céleste est le satellite naturel de la Terre ?', 'La Lune', 'Mars', 'Vénus', 'Le Soleil', 'La Lune est le seul satellite naturel permanent de la Terre.'),
(3, 1, 'Quel organe utilise-t-on principalement pour entendre ?', 'L’oreille', 'L’œil', 'Le nez', 'La langue', 'L’oreille transforme les vibrations sonores en signaux nerveux.'),
(3, 2, 'Quel gaz les plantes absorbent-elles pour fixer le carbone lors de la photosynthèse ?', 'Dioxyde de carbone', 'Dioxygène', 'Diazote', 'Hélium', 'La photosynthèse utilise le CO₂ comme source de carbone.'),
(3, 2, 'Quelle est l’unité SI de la résistance électrique ?', 'Ohm', 'Volt', 'Watt', 'Ampère', 'L’ohm est le rapport entre une tension et un courant.'),
(3, 2, 'Quel type de cellule sanguine transporte principalement l’oxygène ?', 'Globule rouge', 'Plaquette', 'Lymphocyte', 'Neutrophile', 'L’hémoglobine des globules rouges fixe l’oxygène.'),
(3, 2, 'Comment appelle-t-on le passage direct d’un solide à un gaz ?', 'Sublimation', 'Condensation', 'Fusion', 'Solidification', 'La sublimation contourne l’état liquide.'),
(3, 2, 'Quel est le symbole chimique du potassium ?', 'K', 'P', 'Po', 'Pt', 'K vient de kalium, le nom latin du potassium.'),
(3, 3, 'Quel est l’effet de la transcription inverse ?', 'Produire de l’ADN à partir d’ARN', 'Produire de l’ARN à partir d’ADN', 'Produire une protéine à partir d’ARN', 'Doubler le nombre de chromosomes', 'La transcriptase inverse synthétise une séquence d’ADN complémentaire d’un ARN.'),
(3, 3, 'Quel phénomène explique le décalage des raies spectrales d’une étoile qui s’éloigne à faible vitesse ?', 'Effet Doppler', 'Effet photoélectrique', 'Diffraction de Bragg', 'Polarisation', 'Le mouvement d’éloignement décale les raies vers de plus grandes longueurs d’onde.'),
(3, 3, 'Quel minéral définit la dureté 7 sur l’échelle de Mohs ?', 'Quartz', 'Fluorine', 'Calcite', 'Corindon', 'Le quartz raye l’orthose, classée 6, et est rayé par la topaze, classée 8.'),
(3, 3, 'Quelle particule est émise lors d’une désintégration bêta moins, en plus de l’électron ?', 'Un antineutrino électronique', 'Un positron', 'Un proton', 'Un noyau d’hélium', 'Un neutron se transforme en proton en émettant un électron et un antineutrino électronique.'),
(3, 3, 'Quelle liaison relie deux acides aminés dans une chaîne protéique ?', 'Liaison peptidique', 'Liaison glycosidique', 'Liaison phosphodiester', 'Liaison métallique', 'Une liaison peptidique unit le groupe carboxyle d’un acide aminé au groupe amine d’un autre.'),
(3, 3, 'Dans quelle unité SI mesure-t-on un flux magnétique ?', 'Weber', 'Tesla', 'Henry', 'Farad', 'Le weber mesure le flux magnétique, tandis que le tesla mesure la densité de flux.');

START TRANSACTION;

INSERT INTO questions (id_categorie, id_difficulte, enonce, explication, statut_validation)
SELECT c.id_categorie, n.niveau, n.enonce, n.explication, 'validee'
FROM questions_niveaux n
JOIN categories c ON c.nom_categorie = CASE n.theme_id WHEN 1 THEN 'Géographie' ELSE 'Sciences' END
WHERE NOT EXISTS (
    SELECT 1 FROM questions q WHERE q.id_categorie = c.id_categorie AND q.enonce = n.enonce
);

CREATE TEMPORARY TABLE questions_niveaux_a_remplir AS
SELECT q.id_question, n.bonne, n.choix_b, n.choix_c, n.choix_d
FROM questions_niveaux n
JOIN categories c ON c.nom_categorie = CASE n.theme_id WHEN 1 THEN 'Géographie' ELSE 'Sciences' END
JOIN questions q ON q.id_categorie = c.id_categorie AND q.enonce = n.enonce
WHERE NOT EXISTS (SELECT 1 FROM reponses r WHERE r.id_question = q.id_question);

INSERT INTO reponses (id_question, texte_reponse, est_correcte)
SELECT q.id_question,
       CASE MOD(p.position + q.id_question, 4)
           WHEN 0 THEN q.bonne WHEN 1 THEN q.choix_b WHEN 2 THEN q.choix_c ELSE q.choix_d
       END,
       MOD(p.position + q.id_question, 4) = 0
FROM questions_niveaux_a_remplir q
CROSS JOIN (SELECT 0 AS position UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3) p
ORDER BY q.id_question, p.position;

COMMIT;
DROP TEMPORARY TABLE questions_niveaux_a_remplir;
DROP TEMPORARY TABLE questions_niveaux;
