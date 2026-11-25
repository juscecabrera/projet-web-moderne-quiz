USE quiz_culture_generale;

CREATE TABLE IF NOT EXISTS photos_profils (
    id_utilisateur INT UNSIGNED PRIMARY KEY,
    image_webp BLOB NOT NULL,
    CONSTRAINT fk_photo_utilisateur FOREIGN KEY (id_utilisateur)
        REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE
) ENGINE = InnoDB;
