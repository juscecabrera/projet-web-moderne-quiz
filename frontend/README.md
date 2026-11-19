# Frontend — Quiz de culture générale

Interface initiale en HTML, CSS et JavaScript (sans framework). Elle sert de base commune à tous les issues : elle n'implémente que le squelette des écrans, la logique métier reste à faire dans chaque issue.

## Lancer l'application

Double-cliquer sur `index.html` (ou l'ouvrir dans le navigateur). Aucun serveur, aucun outil à installer.

Le code utilise des scripts classiques, sans `import`/`export`, qui partagent un objet global `window.Quiz`. L'ordre de chargement est défini dans `index.html` : un fichier ne peut utiliser que ce qui a été chargé avant lui.

## Ce qui est en place

| Écran / élément | Fichier | Issue concerné | État |
|---|---|---|---|
| Accueil, liste des catégories, choix d'un ou plusieurs niveaux, bouton de lancement | `components/HomeScreen.js` | US-01, US-02, US-03 | Implémenté |
| Question, réponses, badge de difficulté, passage à la suivante | `components/QuestionScreen.js` | US-01, US-03 | Implémenté |
| Panneau de correction (bonne réponse, explication) | `components/FeedbackPanel.js` | US-06 | Interface seule |
| Écran de fin : score, bonnes réponses, difficulté moyenne, performances par catégorie et par difficulté | `components/ResultScreen.js` | US-05, US-07 | Implémenté |
| Niveaux de difficulté | `components/DifficultyBadge.js` | US-03 | Sélection à l'accueil et affichage pendant la partie |

L’écran de résultat (US-POC-05) affiche le score final, le nombre de bonnes réponses sur le total et la difficulté moyenne. Le score du POC utilise une base de 1 point par bonne réponse, pondérée par le multiplicateur de difficulté. La difficulté moyenne est calculée sur les niveaux des questions jouées, de 1 à 5.

Les performances (US-POC-07) sont calculées à partir de l'historique des parties terminées (`js/domain/stats.js`) : taux de bonnes réponses par catégorie et par difficulté, avec la catégorie la plus forte et la plus faible. L'historique est conservé dans le `localStorage` du navigateur (`js/core/storage.js`), donc il survit à un rechargement de la page. Côté backend, il faudra le remplacer par les parties enregistrées en base.

## Organisation

| Fichier | Rôle | Équivalent React |
|---|---|---|
| `js/namespace.js` | Crée l'objet global `window.Quiz` | Module racine |
| `js/data/questions.js` | Données de test (miroir de `database/donnees_test.sql`) : `Quiz.CATEGORIES`, `Quiz.QUESTIONS` | Données mockées |
| `js/api/quizApi.js` | `Quiz.api` : `getCategories`, `getPartie`, `getNombreQuestionsDisponibles`, `validerReponse`. La partie est filtrée et répartie selon les niveaux choisis. | Appels API (`fetch`) |
| `js/config/quiz.config.js` | `Quiz.QUIZ_CONFIG`, `Quiz.DIFFICULTES`, `Quiz.getDifficulte` | Constantes / configuration |
| `js/domain/quiz.js` | `Quiz.initialQuizState`, `Quiz.quizReducer`, `Quiz.actions`, sélecteurs | `useReducer` |
| `js/domain/stats.js` | `Quiz.getStatistiques` : taux par catégorie et par difficulté, catégorie la plus forte et la plus faible | Sélecteur (`useMemo`) |
| `js/core/storage.js` | `Quiz.loadHistorique` / `Quiz.saveHistorique` : persistance de l'historique dans le `localStorage` | Persistance (à remplacer par l'API) |
| `js/core/store.js` | `Quiz.createStore` | Contexte / store |
| `js/core/h.js` | `Quiz.h` : création d'éléments DOM | `React.createElement` / JSX |
| `js/components/*.js` | `Quiz.App`, `Quiz.HomeScreen`, etc. : reçoivent leurs données et callbacks en `props` | Composants |
| `js/main.js` | Point d'entrée : branche le store, les appels API et le rendu | `ReactDOM.createRoot` + effets |

Chaque fichier se présente sous la forme `(function (Quiz) { ... })(window.Quiz);` et s'ajoute à `Quiz` ce qu'il exporte. Pour utiliser un élément d'un autre fichier, écrire `Quiz.nomDeLElement`.

## Règles pour étendre l'interface

- Ajouter une action = une constante dans `ACTION_TYPES`, une fonction dans `actions` et un `case` dans le reducer.
- Un composant ne fait que du rendu : il reçoit les données et les callbacks en `props`, sans appeler l'API.
- Les appels asynchrones se font dans `main.js` (handlers), jamais dans un composant.
- Un nouveau fichier doit être ajouté dans `index.html`, après les fichiers dont il dépend.
- Les bonnes réponses ne sont pas envoyées avant la validation d'une réponse (voir le README racine, section 8).

## Migration vers React

1. Chaque `Quiz.X = function X(props)` de `components/` devient un composant React avec les mêmes `props`.
2. `h()` et `store.js` sont remplacés par JSX et `useReducer` (le reducer de `domain/quiz.js` se réutilise tel quel).
3. Les appels de `main.js` deviennent des `useEffect` ou une bibliothèque de requêtes.
4. Les fonctions de `Quiz.api` deviennent de vrais `fetch()` vers le backend.
