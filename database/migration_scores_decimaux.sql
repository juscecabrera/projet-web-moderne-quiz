USE quiz_culture_generale;

ALTER TABLE parties
    MODIFY score_total DECIMAL(8,2) UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE reponses_joueurs
    MODIFY points_obtenus DECIMAL(8,2) UNSIGNED NOT NULL DEFAULT 0;
