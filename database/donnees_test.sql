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
 5, 2, 'validee'),

(11,
 'Quel traité signé en 1648 met fin à la guerre de Trente Ans ?',
 'Les traités de Westphalie, signés en 1648, mettent fin à la guerre de Trente Ans.',
 5, 4, 'validee'),

(12,
 'Quel mathématicien a démontré le dernier théorème de Fermat ?',
 'Andrew Wiles a démontré le dernier théorème de Fermat dans les années 1990.',
 5, 5, 'validee'),

-- GEOGRAPHIE (13 a 29)

(13,
 'Quelle est la capitale de l’Espagne ?',
 'Madrid est la capitale de l’Espagne depuis le XVIe siècle.',
 1, 1, 'validee'),

(14,
 'Quel fleuve traverse Paris ?',
 'La Seine traverse Paris d’est en ouest avant de se jeter dans la Manche.',
 1, 1, 'validee'),

(15,
 'Quel est le plus grand pays du monde par sa superficie ?',
 'La Russie couvre environ 17 millions de km².',
 1, 1, 'validee'),

(16,
 'Sur quel continent se trouve le désert du Kalahari ?',
 'Le Kalahari s’étend sur le Botswana, la Namibie et l’Afrique du Sud.',
 1, 1, 'validee'),

(17,
 'Quelle est la capitale de la Suisse ?',
 'Berne est la capitale fédérale de la Suisse ; Zurich est la ville la plus peuplée.',
 1, 2, 'validee'),

(18,
 'Quel est le plus long fleuve d’Afrique ?',
 'Le Nil, long d’environ 6 650 km, est le plus long fleuve d’Afrique.',
 1, 2, 'validee'),

(19,
 'Dans quel pays se trouve la ville de Kyoto ?',
 'Kyoto, ancienne capitale impériale, est située au Japon.',
 1, 2, 'validee'),

(20,
 'Quelle est la capitale du Brésil ?',
 'Brasília a remplacé Rio de Janeiro comme capitale en 1960.',
 1, 2, 'validee'),

(21,
 'Quel est le plus haut sommet des Alpes ?',
 'Le Mont Blanc culmine à environ 4 806 m, à la frontière entre la France et l’Italie.',
 1, 3, 'validee'),

(22,
 'Quelle est la capitale de la Nouvelle-Zélande ?',
 'Wellington est la capitale de la Nouvelle-Zélande ; Auckland en est la plus grande ville.',
 1, 3, 'validee'),

(23,
 'Quelle mer sépare la péninsule Arabique de l’Afrique ?',
 'La mer Rouge sépare la péninsule Arabique de l’Afrique (Égypte, Soudan, Érythrée).',
 1, 3, 'validee'),

(24,
 'Quelle est la capitale de l’Ouzbékistan ?',
 'Tachkent est la capitale de l’Ouzbékistan ; Samarcande en est une ville historique importante.',
 1, 3, 'validee'),

(25,
 'Quel est le plus profond lac du monde ?',
 'Le lac Baïkal, en Sibérie, atteint environ 1 642 m de profondeur.',
 1, 4, 'validee'),

(26,
 'Quel est le plus petit pays d’Amérique du Sud ?',
 'Le Suriname est le plus petit État d’Amérique du Sud, avec environ 164 000 km².',
 1, 4, 'validee'),

(27,
 'Quelle est la capitale de l’Éthiopie ?',
 'Addis-Abeba est la capitale de l’Éthiopie et le siège de l’Union africaine.',
 1, 4, 'validee'),

(28,
 'Quel est le plus grand désert froid du monde ?',
 'L’Antarctique est le plus grand désert du monde, et le plus froid.',
 1, 5, 'validee'),

(29,
 'Quelle est la capitale officielle de la Tanzanie ?',
 'Dodoma est la capitale officielle de la Tanzanie ; Dar es Salaam reste la plus grande ville.',
 1, 5, 'validee'),

-- HISTOIRE (30 a 46)

(30,
 'En quelle année Napoléon Bonaparte est-il mort ?',
 'Napoléon Ier meurt en exil à Sainte-Hélène le 5 mai 1821.',
 2, 1, 'validee'),

(31,
 'Qui fut le premier président des États-Unis ?',
 'George Washington est le premier président des États-Unis, en fonction de 1789 à 1797.',
 2, 1, 'validee'),

(32,
 'En quelle année la Seconde Guerre mondiale a-t-elle pris fin en Europe ?',
 'La guerre prend fin en Europe avec la capitulation allemande de mai 1945.',
 2, 1, 'validee'),

(33,
 'Quel monument parisien a été construit pour l’Exposition universelle de 1889 ?',
 'La tour Eiffel, conçue par Gustave Eiffel, est inaugurée en 1889.',
 2, 1, 'validee'),

(34,
 'Quel empire a été fondé par Gengis Khan ?',
 'Gengis Khan fonde l’Empire mongol au début du XIIIe siècle.',
 2, 2, 'validee'),

(35,
 'En quelle année l’Espagne a-t-elle rejoint la Communauté économique européenne ?',
 'L’Espagne et le Portugal rejoignent la Communauté économique européenne le 1er janvier 1986.',
 2, 2, 'validee'),

(36,
 'Quel roi britannique régnait lors du naufrage du Titanic en 1912 ?',
 'George V règne de 1910 à 1936 : il est roi lors du naufrage du Titanic.',
 2, 2, 'validee'),

(37,
 'Quel général romain franchit le Rubicon en 49 av. J.-C. ?',
 'En franchissant le Rubicon en 49 av. J.-C., César déclenche la guerre civile contre Pompée.',
 2, 2, 'validee'),

(38,
 'En quelle année la République populaire de Chine a-t-elle été proclamée ?',
 'La République populaire de Chine est proclamée le 1er octobre 1949 par Mao Zedong.',
 2, 3, 'validee'),

(39,
 'Quel dirigeant bolchevique a mené la révolution d’Octobre en 1917 ?',
 'Vladimir Lénine dirige le parti bolchevique, qui prend le pouvoir en octobre 1917.',
 2, 3, 'validee'),

(40,
 'Quel traité de 1494 partage les terres découvertes entre l’Espagne et le Portugal ?',
 'Le traité de Tordesillas, signé en 1494, partage le monde non européen entre l’Espagne et le Portugal.',
 2, 3, 'validee'),

(41,
 'Quelle dynastie gouverne la Chine de 1644 à 1912 ?',
 'La dynastie Qing, d’origine mandchoue, gouverne la Chine de 1644 à 1912.',
 2, 3, 'validee'),

(42,
 'Quel explorateur atteint l’Inde par la mer en 1498 ?',
 'Vasco de Gama atteint Calicut en 1498 en contournant l’Afrique par le cap de Bonne-Espérance.',
 2, 4, 'validee'),

(43,
 'Quel empereur romain a accordé la liberté de culte aux chrétiens en 313 ?',
 'L’édit de Milan, en 313, accorde la liberté de culte aux chrétiens, sous Constantin.',
 2, 4, 'validee'),

(44,
 'En quelle année a eu lieu la bataille de Hastings ?',
 'Guillaume le Conquérant remporte la bataille de Hastings en 1066.',
 2, 4, 'validee'),

(45,
 'Quel pharaon a fait construire le temple d’Abou Simbel ?',
 'Ramsès II fait creuser le temple d’Abou Simbel vers 1250 av. J.-C.',
 2, 5, 'validee'),

(46,
 'En quelle année le Congrès de Vienne s’achève-t-il ?',
 'Le Congrès de Vienne, réuni après la chute de Napoléon, s’achève en juin 1815.',
 2, 5, 'validee'),

-- SCIENCES (47 a 63)

(47,
 'Quel est le symbole chimique du sodium ?',
 'Le sodium a pour symbole Na, du latin natrium.',
 3, 1, 'validee'),

(48,
 'Quel organe produit l’insuline ?',
 'Le pancréas produit l’insuline, hormone qui régule la glycémie.',
 3, 1, 'validee'),

(49,
 'Quelle est la planète la plus proche du Soleil ?',
 'Mercure est la planète la plus proche du Soleil, à environ 58 millions de km.',
 3, 1, 'validee'),

(50,
 'Quelle couleur est la plus déviée par un prisme de verre ?',
 'Le violet est la couleur la plus déviée par un prisme, car sa longueur d’onde est la plus courte.',
 3, 1, 'validee'),

(51,
 'Combien d’os compte le squelette d’un adulte ?',
 'Le squelette d’un adulte compte 206 os.',
 3, 2, 'validee'),

(52,
 'Quelle est la formule chimique du sel de cuisine ?',
 'Le chlorure de sodium, sel de cuisine, a pour formule NaCl.',
 3, 2, 'validee'),

(53,
 'Quel scientifique a découvert la pénicilline en 1928 ?',
 'Alexander Fleming découvre la pénicilline en 1928, en observant une moisissure.',
 3, 2, 'validee'),

(54,
 'Quelle est l’unité de force du Système international ?',
 'Le newton (N) est l’unité de force du Système international.',
 3, 2, 'validee'),

(55,
 'Combien d’os compte une main humaine ?',
 'Chaque main compte 27 os : 8 carpiens, 5 métacarpiens et 14 phalanges.',
 3, 3, 'validee'),

(56,
 'Quel gaz constitue environ 78 % de l’air sec ?',
 'L’azote représente environ 78 % du volume de l’air sec.',
 3, 3, 'validee'),

(57,
 'Quelle est la vitesse du son dans l’air à 20 °C ?',
 'À 20 °C, le son parcourt environ 343 mètres par seconde dans l’air.',
 3, 3, 'validee'),

(58,
 'Quelle loi relie tension, intensité et résistance électriques ?',
 'La loi d’Ohm s’écrit U = R × I.',
 3, 3, 'validee'),

(59,
 'Quel élément chimique a pour numéro atomique 6 ?',
 'Le carbone possède 6 protons : son numéro atomique est donc 6.',
 3, 4, 'validee'),

(60,
 'Quelle particule transmet la force forte qui lie les quarks ?',
 'Le gluon transmet la force forte, qui lie les quarks au sein des protons et des neutrons.',
 3, 4, 'validee'),

(61,
 'Quelle est la valeur approchée du nombre d’Avogadro ?',
 'Le nombre d’Avogadro vaut environ 6,02 × 10²³ entités par mole.',
 3, 4, 'validee'),

(62,
 'Quel physicien a formulé le principe d’exclusion en 1925 ?',
 'Wolfgang Pauli formule le principe d’exclusion en 1925.',
 3, 5, 'validee'),

(63,
 'Quel est le numéro atomique du tungstène ?',
 'Le tungstène (W) possède 74 protons.',
 3, 5, 'validee'),

-- SPORT (64 a 80)

(64,
 'Combien de joueurs composent une équipe de volley-ball sur le terrain ?',
 'Une équipe de volley-ball aligne six joueurs sur le terrain.',
 4, 1, 'validee'),

(65,
 'Combien de joueurs composent une équipe de handball sur le terrain, gardien compris ?',
 'Une équipe de handball compte sept joueurs sur le terrain, gardien compris.',
 4, 1, 'validee'),

(66,
 'Avec quel type de ballon joue-t-on au rugby ?',
 'Le rugby se joue avec un ballon ovale.',
 4, 1, 'validee'),

(67,
 'Combien de points vaut un touchdown au football américain ?',
 'Au football américain, un touchdown rapporte 6 points.',
 4, 1, 'validee'),

(68,
 'Combien de points vaut un panier à trois points au basket-ball ?',
 'Un tir réussi derrière la ligne à trois points vaut 3 points.',
 4, 2, 'validee'),

(69,
 'Combien de sets faut-il gagner pour remporter un match de tennis au meilleur des trois sets ?',
 'Dans un match au meilleur des trois sets, il faut gagner deux sets.',
 4, 2, 'validee'),

(70,
 'Dans quel pays se sont déroulés les premiers Jeux olympiques modernes, en 1896 ?',
 'Les premiers Jeux olympiques modernes se déroulent à Athènes, en Grèce.',
 4, 2, 'validee'),

(71,
 'Quel pays a organisé la Coupe du monde de football 2014 ?',
 'Le Brésil organise la Coupe du monde de football en 2014.',
 4, 2, 'validee'),

(72,
 'Combien de trous compte un parcours de golf standard ?',
 'Un parcours de golf classique compte 18 trous.',
 4, 3, 'validee'),

(73,
 'Combien de manches compte un match de baseball classique ?',
 'Un match de baseball compte neuf manches, appelées innings.',
 4, 3, 'validee'),

(74,
 'Quel est le plus ancien tournoi du Grand Chelem de tennis ?',
 'Wimbledon, créé en 1877, est le plus ancien tournoi du Grand Chelem.',
 4, 3, 'validee'),

(75,
 'Quel pays a remporté la Coupe du monde de rugby 2007 ?',
 'L’Afrique du Sud remporte la Coupe du monde de rugby 2007 à Paris, en battant l’Angleterre 15 à 6.',
 4, 3, 'validee'),

(76,
 'En quelle année la première Coupe du monde de football a-t-elle eu lieu ?',
 'La première Coupe du monde de football se tient en 1930, en Uruguay.',
 4, 4, 'validee'),

(77,
 'Quel boxeur est surnommé « The Greatest » ?',
 'Muhammad Ali, champion du monde des poids lourds à trois reprises, est surnommé « The Greatest ».',
 4, 4, 'validee'),

(78,
 'Combien de médailles d’or Michael Phelps a-t-il remportées à Pékin en 2008 ?',
 'Michael Phelps remporte huit médailles d’or aux Jeux olympiques de Pékin en 2008, un record.',
 4, 4, 'validee'),

(79,
 'Quel est le record du monde du 100 mètres masculin établi par Usain Bolt en 2009 ?',
 'Usain Bolt établit le record à 9,58 s à Berlin en 2009.',
 4, 5, 'validee'),

(80,
 'Quel joueur détient le record de buts en Coupe du monde masculine ?',
 'Miroslav Klose a marqué 16 buts en Coupe du monde, record absolu.',
 4, 5, 'validee'),

-- CULTURE GENERALE (81 a 96)

(81,
 'Qui a écrit le roman « 1984 » ?',
 'George Orwell publie 1984 en 1949.',
 5, 1, 'validee'),

(82,
 'Combien de cordes possède une guitare classique ?',
 'Une guitare classique possède six cordes.',
 5, 1, 'validee'),

(83,
 'Quelle est la couleur du disque central du drapeau du Japon ?',
 'Le drapeau japonais est blanc avec un disque rouge, le « Hinomaru ».',
 5, 1, 'validee'),

(84,
 'Quelle est la capitale du Portugal ?',
 'Lisbonne est la capitale du Portugal ; Porto est la deuxième ville du pays.',
 5, 1, 'validee'),

(85,
 'Qui a peint « La Nuit étoilée » ?',
 'Vincent van Gogh peint La Nuit étoilée en juin 1889, à Saint-Rémy-de-Provence.',
 5, 2, 'validee'),

(86,
 'Quel compositeur a écrit « Les Quatre Saisons » ?',
 'Antonio Vivaldi compose Les Quatre Saisons, publiées en 1725.',
 5, 2, 'validee'),

(87,
 'Quelle est la langue la plus parlée au monde en nombre de locuteurs natifs ?',
 'Le mandarin est la langue la plus parlée au monde en nombre de locuteurs natifs.',
 5, 2, 'validee'),

(88,
 'Qui a écrit « Les Fleurs du mal » ?',
 'Charles Baudelaire publie Les Fleurs du mal en 1857.',
 5, 3, 'validee'),

(89,
 'Quel philosophe a écrit « Du contrat social » ?',
 'Jean-Jacques Rousseau publie Du contrat social en 1762.',
 5, 3, 'validee'),

(90,
 'Quel opéra de Georges Bizet, créé en 1875, met en scène la cigarière Carmen ?',
 'Carmen, créé à Paris en 1875, est l’opéra le plus célèbre de Bizet.',
 5, 3, 'validee'),

(91,
 'Quel écrivain russe a écrit « Guerre et Paix » ?',
 'Léon Tolstoï publie Guerre et Paix entre 1865 et 1869.',
 5, 3, 'validee'),

(92,
 'Quel peintre a réalisé « Le Radeau de la Méduse » ?',
 'Théodore Géricault peint Le Radeau de la Méduse en 1818-1819, aujourd’hui au Louvre.',
 5, 4, 'validee'),

(93,
 'Qui a créé le personnage de Sherlock Holmes ?',
 'Arthur Conan Doyle publie le premier récit de Sherlock Holmes en 1887.',
 5, 4, 'validee'),

(94,
 'Quel roman de Gustave Flaubert, publié en 1857, suit Emma Bovary ?',
 'Madame Bovary, publié en 1857, raconte la vie d’Emma Bovary.',
 5, 4, 'validee'),

(95,
 'Quel roman de James Joyce, publié en 1922, suit une journée à Dublin ?',
 'Ulysse, publié en 1922, retrace une journée de Leopold Bloom à Dublin.',
 5, 5, 'validee'),

(96,
 'Quel compositeur a créé l’opéra « Wozzeck » ?',
 'Alban Berg compose Wozzeck, créé à Berlin en 1925.',
 5, 5, 'validee');


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
(10, 'Molière', FALSE),

-- QUESTION 11 : TRAITES DE WESTPHALIE
(11, 'Le traité d’Utrecht', FALSE),
(11, 'Les traités de Westphalie', TRUE),
(11, 'Le traité de Versailles', FALSE),
(11, 'Le traité de Paris', FALSE),

-- QUESTION 12 : DERNIER THEOREME DE FERMAT
(12, 'Alan Turing', FALSE),
(12, 'David Hilbert', FALSE),
(12, 'Andrew Wiles', TRUE),
(12, 'Benoît Mandelbrot', FALSE),

-- QUESTIONS 13 A 29 : GEOGRAPHIE
(13, 'Barcelone', FALSE),
(13, 'Madrid', TRUE),
(13, 'Séville', FALSE),
(13, 'Valence', FALSE),
(14, 'Seine', TRUE),
(14, 'Loire', FALSE),
(14, 'Rhône', FALSE),
(14, 'Marne', FALSE),
(15, 'Canada', FALSE),
(15, 'Chine', FALSE),
(15, 'Russie', TRUE),
(15, 'États-Unis', FALSE),
(16, 'Asie', FALSE),
(16, 'Afrique', TRUE),
(16, 'Amérique du Sud', FALSE),
(16, 'Australie', FALSE),
(17, 'Zurich', FALSE),
(17, 'Berne', TRUE),
(17, 'Genève', FALSE),
(17, 'Bâle', FALSE),
(18, 'Congo', FALSE),
(18, 'Nil', TRUE),
(18, 'Niger', FALSE),
(18, 'Zambèze', FALSE),
(19, 'Japon', TRUE),
(19, 'Chine', FALSE),
(19, 'Corée du Sud', FALSE),
(19, 'Thaïlande', FALSE),
(20, 'Rio de Janeiro', FALSE),
(20, 'São Paulo', FALSE),
(20, 'Salvador', FALSE),
(20, 'Brasília', TRUE),
(21, 'Cervin', FALSE),
(21, 'Mont Blanc', TRUE),
(21, 'Eiger', FALSE),
(21, 'Mont Rose', FALSE),
(22, 'Auckland', FALSE),
(22, 'Wellington', TRUE),
(22, 'Christchurch', FALSE),
(22, 'Dunedin', FALSE),
(23, 'Mer Rouge', TRUE),
(23, 'Mer Noire', FALSE),
(23, 'Mer d’Arabie', FALSE),
(23, 'Mer Morte', FALSE),
(24, 'Samarcande', FALSE),
(24, 'Tachkent', TRUE),
(24, 'Boukhara', FALSE),
(24, 'Almaty', FALSE),
(25, 'Tanganyika', FALSE),
(25, 'Victoria', FALSE),
(25, 'Baïkal', TRUE),
(25, 'Ontario', FALSE),
(26, 'Uruguay', FALSE),
(26, 'Suriname', TRUE),
(26, 'Guyana', FALSE),
(26, 'Paraguay', FALSE),
(27, 'Nairobi', FALSE),
(27, 'Addis-Abeba', TRUE),
(27, 'Khartoum', FALSE),
(27, 'Asmara', FALSE),
(28, 'Arctique', FALSE),
(28, 'Antarctique', TRUE),
(28, 'Gobi', FALSE),
(28, 'Patagonie', FALSE),
(29, 'Dar es Salaam', FALSE),
(29, 'Dodoma', TRUE),
(29, 'Zanzibar', FALSE),
(29, 'Arusha', FALSE),

-- QUESTIONS 30 A 46 : HISTOIRE
(30, '1815', FALSE),
(30, '1821', TRUE),
(30, '1830', FALSE),
(30, '1848', FALSE),
(31, 'Thomas Jefferson', FALSE),
(31, 'George Washington', TRUE),
(31, 'John Adams', FALSE),
(31, 'Abraham Lincoln', FALSE),
(32, '1943', FALSE),
(32, '1947', FALSE),
(32, '1945', TRUE),
(32, '1950', FALSE),
(33, 'Sacré-Cœur', FALSE),
(33, 'Arc de Triomphe', FALSE),
(33, 'Grand Palais', FALSE),
(33, 'Tour Eiffel', TRUE),
(34, 'Empire ottoman', FALSE),
(34, 'Empire mongol', TRUE),
(34, 'Empire perse', FALSE),
(34, 'Empire byzantin', FALSE),
(35, '1973', FALSE),
(35, '1986', TRUE),
(35, '1995', FALSE),
(35, '1981', FALSE),
(36, 'Édouard VII', FALSE),
(36, 'George V', TRUE),
(36, 'Édouard VIII', FALSE),
(36, 'Georges VI', FALSE),
(37, 'Pompée', FALSE),
(37, 'Marc Antoine', FALSE),
(37, 'Auguste', FALSE),
(37, 'Jules César', TRUE),
(38, '1937', FALSE),
(38, '1949', TRUE),
(38, '1956', FALSE),
(38, '1966', FALSE),
(39, 'Nicolas II', FALSE),
(39, 'Lénine', TRUE),
(39, 'Kerenski', FALSE),
(39, 'Staline', FALSE),
(40, 'Traité de Tordesillas', TRUE),
(40, 'Traité de Madrid', FALSE),
(40, 'Traité de Cateau-Cambrésis', FALSE),
(40, 'Traité de Nimègue', FALSE),
(41, 'Dynastie Ming', FALSE),
(41, 'Dynastie Qing', TRUE),
(41, 'Dynastie Han', FALSE),
(41, 'Dynastie Tang', FALSE),
(42, 'Christophe Colomb', FALSE),
(42, 'Magellan', FALSE),
(42, 'Bartolomeu Dias', FALSE),
(42, 'Vasco de Gama', TRUE),
(43, 'Dioclétien', FALSE),
(43, 'Néron', FALSE),
(43, 'Trajan', FALSE),
(43, 'Constantin Ier', TRUE),
(44, '1066', TRUE),
(44, '1215', FALSE),
(44, '1346', FALSE),
(44, '1415', FALSE),
(45, 'Ramsès II', TRUE),
(45, 'Toutânkhamon', FALSE),
(45, 'Akhenaton', FALSE),
(45, 'Thoutmôsis III', FALSE),
(46, '1789', FALSE),
(46, '1815', TRUE),
(46, '1830', FALSE),
(46, '1848', FALSE),

-- QUESTIONS 47 A 63 : SCIENCES
(47, 'So', FALSE),
(47, 'Na', TRUE),
(47, 'Sd', FALSE),
(47, 'Sn', FALSE),
(48, 'Foie', FALSE),
(48, 'Rein', FALSE),
(48, 'Pancréas', TRUE),
(48, 'Estomac', FALSE),
(49, 'Vénus', FALSE),
(49, 'Mars', FALSE),
(49, 'Terre', FALSE),
(49, 'Mercure', TRUE),
(50, 'Violet', TRUE),
(50, 'Rouge', FALSE),
(50, 'Jaune', FALSE),
(50, 'Vert', FALSE),
(51, '180', FALSE),
(51, '206', TRUE),
(51, '250', FALSE),
(51, '312', FALSE),
(52, 'KCl', FALSE),
(52, 'NaCl', TRUE),
(52, 'CaCl2', FALSE),
(52, 'NaOH', FALSE),
(53, 'Alexander Fleming', TRUE),
(53, 'Louis Pasteur', FALSE),
(53, 'Marie Curie', FALSE),
(53, 'Robert Koch', FALSE),
(54, 'Joule', FALSE),
(54, 'Newton', TRUE),
(54, 'Watt', FALSE),
(54, 'Pascal', FALSE),
(55, '20', FALSE),
(55, '27', TRUE),
(55, '32', FALSE),
(55, '40', FALSE),
(56, 'Azote', TRUE),
(56, 'Oxygène', FALSE),
(56, 'Dioxyde de carbone', FALSE),
(56, 'Argon', FALSE),
(57, '1 500 m/s', FALSE),
(57, '343 m/s', TRUE),
(57, '3 000 m/s', FALSE),
(57, '30 m/s', FALSE),
(58, 'Loi de Newton', FALSE),
(58, 'Loi d’Ohm', TRUE),
(58, 'Loi de Hooke', FALSE),
(58, 'Loi de Faraday', FALSE),
(59, 'Azote', FALSE),
(59, 'Bore', FALSE),
(59, 'Carbone', TRUE),
(59, 'Oxygène', FALSE),
(60, 'Gluon', TRUE),
(60, 'Photon', FALSE),
(60, 'Électron', FALSE),
(60, 'Neutrino', FALSE),
(61, '3,00 × 10⁸', FALSE),
(61, '6,02 × 10²³', TRUE),
(61, '1,60 × 10⁻¹⁹', FALSE),
(61, '9,81 × 10⁰', FALSE),
(62, 'Niels Bohr', FALSE),
(62, 'Enrico Fermi', FALSE),
(62, 'Wolfgang Pauli', TRUE),
(62, 'Paul Dirac', FALSE),
(63, '47', FALSE),
(63, '74', TRUE),
(63, '82', FALSE),
(63, '29', FALSE),

-- QUESTIONS 64 A 80 : SPORT
(64, '5', FALSE),
(64, '6', TRUE),
(64, '7', FALSE),
(64, '9', FALSE),
(65, '5', FALSE),
(65, '6', FALSE),
(65, '7', TRUE),
(65, '8', FALSE),
(66, 'Football', FALSE),
(66, 'Basket-ball', FALSE),
(66, 'Rugby', TRUE),
(66, 'Hockey sur glace', FALSE),
(67, 'Rugby', FALSE),
(67, 'Baseball', FALSE),
(67, 'Hockey sur glace', FALSE),
(67, 'Football américain', TRUE),
(68, '1', FALSE),
(68, '2', FALSE),
(68, '3', TRUE),
(68, '4', FALSE),
(69, '1', FALSE),
(69, '2', TRUE),
(69, '3', FALSE),
(69, '4', FALSE),
(70, 'Grèce', TRUE),
(70, 'France', FALSE),
(70, 'Italie', FALSE),
(70, 'Allemagne', FALSE),
(71, 'Afrique du Sud', FALSE),
(71, 'Brésil', TRUE),
(71, 'Russie', FALSE),
(71, 'Qatar', FALSE),
(72, '9', FALSE),
(72, '12', FALSE),
(72, '18', TRUE),
(72, '27', FALSE),
(73, '6', FALSE),
(73, '9', TRUE),
(73, '7', FALSE),
(73, '12', FALSE),
(74, 'Roland-Garros', FALSE),
(74, 'US Open', FALSE),
(74, 'Open d’Australie', FALSE),
(74, 'Wimbledon', TRUE),
(75, 'Nouvelle-Zélande', FALSE),
(75, 'Australie', FALSE),
(75, 'Afrique du Sud', TRUE),
(75, 'Angleterre', FALSE),
(76, '1924', FALSE),
(76, '1930', TRUE),
(76, '1934', FALSE),
(76, '1950', FALSE),
(77, 'Mike Tyson', FALSE),
(77, 'Muhammad Ali', TRUE),
(77, 'Joe Frazier', FALSE),
(77, 'Rocky Marciano', FALSE),
(78, '6', FALSE),
(78, '7', FALSE),
(78, '8', TRUE),
(78, '9', FALSE),
(79, '9,69 s', FALSE),
(79, '9,58 s', TRUE),
(79, '9,79 s', FALSE),
(79, '9,93 s', FALSE),
(80, 'Pelé', FALSE),
(80, 'Cristiano Ronaldo', FALSE),
(80, 'Miroslav Klose', TRUE),
(80, 'Lionel Messi', FALSE),

-- QUESTIONS 81 A 96 : CULTURE GENERALE
(81, 'Aldous Huxley', FALSE),
(81, 'George Orwell', TRUE),
(81, 'Ray Bradbury', FALSE),
(81, 'Jules Verne', FALSE),
(82, '4', FALSE),
(82, '5', FALSE),
(82, '6', TRUE),
(82, '7', FALSE),
(83, 'Bleu', FALSE),
(83, 'Rouge', TRUE),
(83, 'Vert', FALSE),
(83, 'Jaune', FALSE),
(84, 'Porto', FALSE),
(84, 'Lisbonne', TRUE),
(84, 'Madrid', FALSE),
(84, 'Séville', FALSE),
(85, 'Monet', FALSE),
(85, 'Cézanne', FALSE),
(85, 'Vincent van Gogh', TRUE),
(85, 'Gauguin', FALSE),
(86, 'Vivaldi', TRUE),
(86, 'Bach', FALSE),
(86, 'Mozart', FALSE),
(86, 'Haydn', FALSE),
(87, 'Anglais', FALSE),
(87, 'Espagnol', FALSE),
(87, 'Hindi', FALSE),
(87, 'Mandarin', TRUE),
(88, 'Verlaine', FALSE),
(88, 'Charles Baudelaire', TRUE),
(88, 'Rimbaud', FALSE),
(88, 'Mallarmé', FALSE),
(89, 'Montesquieu', FALSE),
(89, 'Jean-Jacques Rousseau', TRUE),
(89, 'Voltaire', FALSE),
(89, 'Diderot', FALSE),
(90, 'Carmen', TRUE),
(90, 'Faust', FALSE),
(90, 'Tosca', FALSE),
(90, 'Aida', FALSE),
(91, 'Dostoïevski', FALSE),
(91, 'Tolstoï', TRUE),
(91, 'Tchekhov', FALSE),
(91, 'Pouchkine', FALSE),
(92, 'Delacroix', FALSE),
(92, 'Théodore Géricault', TRUE),
(92, 'Ingres', FALSE),
(92, 'David', FALSE),
(93, 'Agatha Christie', FALSE),
(93, 'Arthur Conan Doyle', TRUE),
(93, 'Edgar Allan Poe', FALSE),
(93, 'Gaston Leroux', FALSE),
(94, 'Madame Bovary', TRUE),
(94, 'Le Rouge et le Noir', FALSE),
(94, 'Eugénie Grandet', FALSE),
(94, 'Germinal', FALSE),
(95, 'Finnegans Wake', FALSE),
(95, 'Ulysse', TRUE),
(95, 'Dubliners', FALSE),
(95, 'Le Portrait de l’artiste en jeune homme', FALSE),
(96, 'Arnold Schönberg', FALSE),
(96, 'Paul Hindemith', FALSE),
(96, 'Alban Berg', TRUE),
(96, 'Kurt Weill', FALSE);


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