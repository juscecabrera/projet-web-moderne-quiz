# projet-web-moderne-quiz

## 1. Présentation

Ce dépôt contient le quiz de culture générale, ses pages PHP et les scripts SQL d’installation.

La base de données permet de gérer les utilisateurs, les catégories, les niveaux de difficulté, les questions, les propositions de réponses et les parties jouées.

**Nom de la base de données :** `quiz_culture_generale`

**Système de gestion :** MySQL 8

## 2. Organisation des fichiers

| Fichier | Description |
|---|---|
| `schema.sql` | Création des tables et de leurs relations |
| `donnees_test.sql` | Insertion des catégories, difficultés, questions et réponses |
| `migration_scores_decimaux.sql` | Migration des scores pour une base déjà installée |

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

Si la base existe déjà avec des scores entiers, importer `database/migration_scores_decimaux.sql` pour conserver les demi-points.

### Étape 3 — Ajouter les données de test

1. Sélectionner la base `quiz_culture_generale` dans phpMyAdmin.
2. Cliquer sur **Importer**.
3. Sélectionner le fichier `database/donnees_test.sql`.
4. Lancer l'importation.

**Attention :** importer les données de test une seule fois, dans une base vide, afin d'éviter les doublons.

### Lancer le parcours PHP en local

Avec PHP 8.2 et les extensions `pdo_mysql` et `mbstring`, lancer depuis la racine du dépôt :

```sh
php -S 127.0.0.1:8080 -t public
```

Ouvrir [le quiz](http://127.0.0.1:8080/) et cliquer sur **Créer un compte**.
La configuration locale par défaut utilise MySQL sur `127.0.0.1:3306` avec la base `quiz_culture_generale` et l’utilisateur `root` sans mot de passe.
Définir `QUIZ_DB_HOST`, `QUIZ_DB_PORT`, `QUIZ_DB_NAME`, `QUIZ_DB_USER` et `QUIZ_DB_PASSWORD` si nécessaire.
Définir `QUIZ_QUESTION_LIMIT` entre 1 et 10 pour modifier la longueur des nouvelles parties. La valeur par défaut est 10.

Avec Apache dans WampServer et le projet dans `C:\wamp64\www\projet-web-moderne-quiz`, ouvrir [le point d’entrée PHP](http://localhost/projet-web-moderne-quiz/public/index.php). En hébergement, utiliser `public/` comme racine web.

## 5. Données disponibles

Après l'importation, la base contient :

| Données | Quantité |
|---|---:|
| Catégories | 5 |
| Niveaux de difficulté | 5 |
| Questions | 96 |
| Propositions de réponses | 384 |

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

Les résultats attendus sont : 5 catégories, 5 difficultés, 96 questions et 384 réponses.

## 8. Intégration avec l'application

Le parcours PHP utilise la session pour identifier le joueur, charge les thèmes et les questions depuis MySQL, puis corrige et enregistre les réponses côté serveur. Le bilan charge la partie terminée et vérifie qu’elle appartient au compte connecté.

La partie propose jusqu’à dix questions du thème, réparties entre les difficultés disponibles. Les propositions sont affichées avant la correction. Le score est calculé à partir des multiplicateurs de difficulté enregistrés dans MySQL.

Le prototype JavaScript reste accessible via `public/index.html`. Voir le [flux du quiz](docs/flux-quiz.md) et les [contrôles de livraison](tests/recette.md).
