# DÉCLIC

DÉCLIC est un quiz de culture générale en français. Choisis un thème et une difficulté, réponds à dix questions et découvre la correction après chaque réponse. Le bilan récapitule les points gagnés. Le classement cumule les points des parties terminées, thème par thème.

Les six thèmes sont Géographie, Histoire, Sciences, Sport, Culture générale et Arts et divertissement. Les difficultés proposées sont Facile, Moyen et Difficile. Chaque joueur dispose d’un pseudo public unique et peut ajouter une photo depuis **Mon compte**.

## Stack

- **HTML, CSS et JavaScript** pour l’interface et ses interactions.
- **PHP 8.2** et **PDO** pour les formulaires, les sessions et les requêtes SQL.
- **MySQL 8** pour les comptes, les questions, les parties et les scores.
- **WampServer et phpMyAdmin** pour l’installation locale sous Windows.

## Installation locale

### 1. Récupérer le projet

Sur une nouvelle installation, clonez le dépôt dans `C:\wamp64\www` et utilisez la branche d’intégration `develop` dès que la version DÉCLIC y est fusionnée.

```powershell
cd C:\wamp64\www
git clone https://github.com/juscecabrera/projet-web-moderne-quiz.git
cd projet-web-moderne-quiz
git switch develop
git pull --ff-only origin develop
```

Si le dépôt est déjà cloné, commencez par `git switch develop`, puis `git pull --ff-only origin develop`. Si Git signale des modifications locales, enregistrez-les sur votre branche de travail avant de changer de branche.

### 2. Préparer MySQL dans phpMyAdmin

Démarrez WampServer, puis ouvrez phpMyAdmin. Sélectionnez **MySQL** si WampServer propose aussi MariaDB.

**Nouvelle installation.** Dans l’onglet **Importer**, chargez les trois fichiers dans cet ordre :

| Ordre | Fichier | Rôle |
| --- | --- | --- |
| 1 | [database/schema.sql](database/schema.sql) | Crée la base `quiz_culture_generale` et ses tables. |
| 2 | [database/donnees_test.sql](database/donnees_test.sql) | Insère les catégories, difficultés, questions et réponses initiales. |
| 3 | [database/questions_niveaux.sql](database/questions_niveaux.sql) | Ajoute les questions complémentaires et applique les corrections de Sport. |

Après le premier import, sélectionnez `quiz_culture_generale` pour les suivants. Une base neuve complète contient **6 thèmes, 547 questions et 2 188 choix de réponse**. Le schéma actuel contient déjà les scores décimaux et la table des photos.

**Base existante.** Gardez la base et ses données. Appliquez les fichiers adaptés à son état :

- [migration_scores_decimaux.sql](database/migration_scores_decimaux.sql) si les colonnes de score de l’ancien schéma sont encore entières.
- [migration_photos_profils.sql](database/migration_photos_profils.sql) si la table `photos_profils` est absente.
- [questions_niveaux.sql](database/questions_niveaux.sql) pour ajouter le nouveau stock et appliquer les corrections. Il peut être réimporté sans dupliquer les questions ni les réponses existantes.

Réservez `donnees_test.sql` à l’installation initiale. Il insère des identifiants fixes qui existent déjà sur une base peuplée.

### 3. Ouvrir le quiz

Avec Apache démarré et le dépôt dans `C:\wamp64\www\projet-web-moderne-quiz`, ouvrez [DÉCLIC sur localhost](http://localhost/projet-web-moderne-quiz/public/index.php) et cliquez sur **Créer un compte**.

Vous pouvez aussi utiliser le serveur intégré de PHP. Depuis la racine du dépôt :

```powershell
php -S 127.0.0.1:8080 -t public
```

Ouvrez ensuite [DÉCLIC sur le port 8080](http://127.0.0.1:8080/). Le site PHP démarre par `public/index.php`. `public/index.html` reste le prototype JavaScript.

PHP a besoin de `pdo_mysql` et `mbstring`. Pour les photos, activez aussi `gd` avec JPEG, PNG et WebP. Configurez `upload_max_filesize` à au moins `3M` et `post_max_size` au-dessus de cette limite.

Par défaut, l’appli utilise MySQL à l’adresse `127.0.0.1:3306`, la base `quiz_culture_generale` et l’utilisateur local `root` avec un mot de passe vide. Si votre configuration diffère, définissez `QUIZ_DB_HOST`, `QUIZ_DB_PORT`, `QUIZ_DB_NAME`, `QUIZ_DB_USER` et `QUIZ_DB_PASSWORD` dans l’environnement du serveur PHP. La variable `QUIZ_QUESTION_LIMIT` permet de régler la longueur d’une partie entre 1 et 10 pour les tests.

## Contribuer

`develop` reçoit les contributions de l’équipe. `main` sert aux versions validées pour la livraison. Pour proposer un changement, partez de `develop` à jour et créez votre propre branche.

```powershell
git switch develop
git pull --ff-only origin develop
git switch -c feature/nom-du-changement
```

Testez vos modifications localement et faites un commit. Publiez **votre branche de travail** pour que GitHub puisse comparer les changements, puis ouvrez une **pull request vers `develop`**.

```powershell
git push -u origin feature/nom-du-changement
```

Dans la PR, décrivez les changements, les tests effectués et les éventuelles instructions SQL. Attendez la revue de l’équipe avant la fusion. Les changements destinés à `main` passent par une PR de livraison depuis `develop`.

## Organisation du dépôt

| Dossier | Contenu |
| --- | --- |
| `public/` | Pages du quiz et point d’entrée PHP |
| `public/assets/` | Styles, scripts JavaScript et images |
| `src/` | Sessions, connexion PDO, jeu et photos de compte |
| `database/` | Schéma, données de départ et migrations |
| `docs/` | [Flux de l’application](docs/flux-quiz.md) |
| `tests/` | [Fiche de recette](tests/recette.md) |

Pour un premier contrôle, créez un compte, jouez une partie et vérifiez son bilan. Ouvrez ensuite le classement du thème pour retrouver vos points. Vous pouvez aussi ouvrir un autre thème pour vérifier que les résultats restent séparés.
