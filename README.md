# projet-web-moderne-quiz

## 1. Présentation

Ce dossier contient les scripts SQL nécessaires à l'installation de la base de données de notre application web de quiz de culture générale.

La base de données permet de gérer les utilisateurs, les catégories, les niveaux de difficulté, les questions, les propositions de réponses et les parties jouées.

**Nom de la base de données :** `quiz_culture_generale`

**Système de gestion :** MySQL 8

## 2. Organisation des fichiers

| Fichier | Description |
|---|---|
| `schema.sql` | Création des tables et de leurs relations |
| `donnees_test.sql` | Insertion des catégories, difficultés, questions et réponses |

## 3. Structure de la base de données

La base contient 7 tables :

| Table | Rôle |
|---|---|
| `utilisateurs` | Stockage des informations des joueurs |
| `categories` | Organisation des questions par thème |
| `difficultes` | Gestion des niveaux de difficulté et des multiplicateurs |
| `questions` | Stockage des questions du quiz |
| `reponses` | Stockage des propositions de réponses |
| `parties` | Enregistrement des sessions de jeu |
| `reponses_joueurs` | Enregistrement des réponses données pendant une partie |

Les tables sont reliées par des clés étrangères afin d'assurer la cohérence des données.

## 4. Installation de la base de données

### Prérequis

- MySQL 8
- WAMP ou un environnement MySQL équivalent
- phpMyAdmin ou un client SQL

### Étape 1 — Démarrer MySQL

Démarrer WAMP et vérifier que le service MySQL fonctionne.

### Étape 2 — Créer les tables

1. Ouvrir phpMyAdmin.
2. Cliquer sur l'onglet **Importer**.
3. Sélectionner le fichier `database/schema.sql`.
4. Lancer l'importation.

Le script crée la base `quiz_culture_generale` et ses sept tables.

### Étape 3 — Ajouter les données de test

1. Sélectionner la base `quiz_culture_generale` dans phpMyAdmin.
2. Cliquer sur **Importer**.
3. Sélectionner le fichier `database/donnees_test.sql`.
4. Lancer l'importation.

**Attention :** importer les données de test une seule fois, dans une base vide, afin d'éviter les doublons.

## 5. Données disponibles

Après l'importation, la base contient :

| Données | Quantité |
|---|---:|
| Catégories | 5 |
| Niveaux de difficulté | 5 |
| Questions | 10 |
| Propositions de réponses | 40 |

Les catégories disponibles sont : Géographie, Histoire, Sciences, Sport et Culture générale.

Les niveaux de difficulté sont : Facile, Intermédiaire, Difficile, Expert et Master.

## 6. Consulter les questions et leurs réponses

Pour afficher les questions avec leurs propositions, exécuter cette requête dans phpMyAdmin :

```sql
SELECT
    q.enonce AS question,
    r.texte_reponse AS proposition,
    r.est_correcte AS bonne_reponse
FROM questions q
JOIN reponses r
    ON q.id_question = r.id_question
ORDER BY q.id_question, r.id_reponse;
```

La colonne `bonne_reponse` indique si la proposition est correcte :

- `1` : réponse correcte.
- `0` : réponse incorrecte.

## 7. Vérifier les données

Exécuter cette requête pour vérifier le nombre d'enregistrements :

```sql
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
```

Les résultats attendus sont : 5 catégories, 5 difficultés, 10 questions et 40 réponses.

## 8. Intégration avec l'application

La base de données est destinée à être utilisée par le backend de l'application.

Le backend devra notamment :

- Récupérer les questions selon leur catégorie et leur difficulté.
- Fournir les propositions de réponses au frontend.
- Vérifier les réponses des joueurs.
- Calculer les scores.
- Enregistrer les parties et les résultats.

Les bonnes réponses ne doivent pas être transmises au frontend avant la validation de la réponse du joueur.

## 9. État d'avancement

La structure initiale de la base de données et les données de test ont été préparées.

L'intégration avec le backend et les tests fonctionnels complets restent à réaliser.