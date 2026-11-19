-- ============================================================
-- PROJET : QUIZ DE CULTURE GENERALE GAMIFIE
-- FICHIER : schema.sql
-- BASE DE DONNEES : quiz_culture_generale
-- SGBD : MySQL 8
-- VERSION : 1.0
-- ============================================================

-- DESCRIPTION :
-- Ce script permet de créer la base de données
-- de l'application Quiz de culture générale.
--
-- Il contient :
-- 1. Utilisateurs
-- 2. Categories
-- 3. Difficultes
-- 4. Questions
-- 5. Reponses
-- 6. Parties
-- 7. Reponses des joueurs
--
-- Les relations sont assurées par des clés étrangères.
-- Toutes les tables utilisent le moteur InnoDB.
-- ============================================================


-- ============================================================
-- 1. CREATION DE LA BASE DE DONNEES
-- ============================================================

CREATE DATABASE IF NOT EXISTS quiz_culture_generale
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE quiz_culture_generale;


-- ============================================================
-- 2. TABLE UTILISATEURS
-- ============================================================

CREATE TABLE IF NOT EXISTS utilisateurs (

    id_utilisateur INT UNSIGNED AUTO_INCREMENT,

    pseudo VARCHAR(50) NOT NULL,

    email VARCHAR(150) NOT NULL,

    mot_de_passe_hash VARCHAR(255) NOT NULL,

    xp_total INT UNSIGNED NOT NULL DEFAULT 0,

    niveau INT UNSIGNED NOT NULL DEFAULT 1,

    coins INT UNSIGNED NOT NULL DEFAULT 0,

    date_inscription DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_utilisateurs
        PRIMARY KEY (id_utilisateur),

    CONSTRAINT uq_utilisateurs_pseudo
        UNIQUE (pseudo),

    CONSTRAINT uq_utilisateurs_email
        UNIQUE (email),

    CONSTRAINT chk_utilisateurs_niveau
        CHECK (niveau >= 1)

) ENGINE = InnoDB;


-- ============================================================
-- 3. TABLE CATEGORIES
-- ============================================================

CREATE TABLE IF NOT EXISTS categories (

    id_categorie INT UNSIGNED AUTO_INCREMENT,

    nom_categorie VARCHAR(100) NOT NULL,

    description TEXT,

    CONSTRAINT pk_categories
        PRIMARY KEY (id_categorie),

    CONSTRAINT uq_categories_nom
        UNIQUE (nom_categorie)

) ENGINE = InnoDB;


-- ============================================================
-- 4. TABLE DIFFICULTES
-- ============================================================

CREATE TABLE IF NOT EXISTS difficultes (

    id_difficulte INT UNSIGNED AUTO_INCREMENT,

    libelle VARCHAR(50) NOT NULL,

    multiplicateur DECIMAL(4,2) NOT NULL,

    CONSTRAINT pk_difficultes
        PRIMARY KEY (id_difficulte),

    CONSTRAINT uq_difficultes_libelle
        UNIQUE (libelle),

    CONSTRAINT chk_difficultes_multiplicateur
        CHECK (multiplicateur > 0)

) ENGINE = InnoDB;


-- ============================================================
-- 5. TABLE QUESTIONS
-- ============================================================

CREATE TABLE IF NOT EXISTS questions (

    id_question INT UNSIGNED AUTO_INCREMENT,

    enonce TEXT NOT NULL,

    explication TEXT,

    source_question VARCHAR(255),

    langue VARCHAR(10) NOT NULL DEFAULT 'fr',

    type_question VARCHAR(50) NOT NULL DEFAULT 'qcm',

    statut_validation VARCHAR(30)
        NOT NULL DEFAULT 'en_attente',

    date_creation DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    id_categorie INT UNSIGNED NOT NULL,

    id_difficulte INT UNSIGNED NOT NULL,

    CONSTRAINT pk_questions
        PRIMARY KEY (id_question),

    CONSTRAINT fk_questions_categories
        FOREIGN KEY (id_categorie)
        REFERENCES categories(id_categorie)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_questions_difficultes
        FOREIGN KEY (id_difficulte)
        REFERENCES difficultes(id_difficulte)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT chk_questions_statut
        CHECK (
            statut_validation IN (
                'en_attente',
                'validee',
                'rejetee'
            )
        )

) ENGINE = InnoDB;


-- ============================================================
-- 6. TABLE REPONSES
-- ============================================================

CREATE TABLE IF NOT EXISTS reponses (

    id_reponse INT UNSIGNED AUTO_INCREMENT,

    id_question INT UNSIGNED NOT NULL,

    texte_reponse TEXT NOT NULL,

    est_correcte BOOLEAN NOT NULL DEFAULT FALSE,

    CONSTRAINT pk_reponses
        PRIMARY KEY (id_reponse),

    CONSTRAINT uq_reponses_question_id
        UNIQUE (id_question, id_reponse),

    CONSTRAINT fk_reponses_questions
        FOREIGN KEY (id_question)
        REFERENCES questions(id_question)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE = InnoDB;


-- ============================================================
-- 7. TABLE PARTIES
-- ============================================================

CREATE TABLE IF NOT EXISTS parties (

    id_partie INT UNSIGNED AUTO_INCREMENT,

    id_utilisateur INT UNSIGNED NULL,

    date_debut DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    date_fin DATETIME NULL,

    score_total INT UNSIGNED NOT NULL DEFAULT 0,

    nombre_bonnes_reponses INT UNSIGNED
        NOT NULL DEFAULT 0,

    statut VARCHAR(30) NOT NULL DEFAULT 'en_cours',

    CONSTRAINT pk_parties
        PRIMARY KEY (id_partie),

    CONSTRAINT fk_parties_utilisateurs
        FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateurs(id_utilisateur)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT chk_parties_statut
        CHECK (
            statut IN (
                'en_cours',
                'terminee',
                'abandonnee'
            )
        ),

    CONSTRAINT chk_parties_dates
        CHECK (
            date_fin IS NULL
            OR date_fin >= date_debut
        )

) ENGINE = InnoDB;


-- ============================================================
-- 8. TABLE REPONSES_JOUEURS
-- ============================================================

CREATE TABLE IF NOT EXISTS reponses_joueurs (

    id_reponse_joueur INT UNSIGNED AUTO_INCREMENT,

    id_partie INT UNSIGNED NOT NULL,

    id_question INT UNSIGNED NOT NULL,

    id_reponse INT UNSIGNED NULL,

    temps_reponse DECIMAL(8,2) NULL,

    est_correcte BOOLEAN NOT NULL DEFAULT FALSE,

    points_obtenus INT UNSIGNED NOT NULL DEFAULT 0,

    date_reponse DATETIME NOT NULL
        DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_reponses_joueurs
        PRIMARY KEY (id_reponse_joueur),

    CONSTRAINT uq_partie_question
        UNIQUE (id_partie, id_question),

    CONSTRAINT fk_rj_parties
        FOREIGN KEY (id_partie)
        REFERENCES parties(id_partie)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_rj_questions
        FOREIGN KEY (id_question)
        REFERENCES questions(id_question)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_rj_reponses
        FOREIGN KEY (id_question, id_reponse)
        REFERENCES reponses(id_question, id_reponse)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT chk_rj_temps
        CHECK (
            temps_reponse IS NULL
            OR temps_reponse >= 0
        )

) ENGINE = InnoDB;


-- ============================================================
-- FIN DU SCRIPT SQL
-- ============================================================