# Frontend — Quiz de culture générale

Version HTML, CSS et JavaScript (sans framework), structurée pour faciliter une migration future vers React.

## Lancer l'application

Les modules JavaScript ne fonctionnent pas en ouvrant directement le fichier `index.html`. Il faut passer par un serveur local, par exemple depuis le dossier `frontend/` :

```bash
python3 -m http.server 8000
```

Puis ouvrir `http://localhost:8000` dans le navigateur.

## Organisation

| Dossier / fichier | Rôle | Équivalent React |
|---|---|---|
| `js/data/questions.js` | Données de test (miroir de `database/donnees_test.sql`) | Appel API |
| `js/domain/quiz.js` | Logique pure : tirage des questions, reducer, actions | Hooks / reducer |
| `js/core/store.js` | Store d'état et abonnements | `useReducer` / contexte |
| `js/core/h.js` | Création d'éléments DOM | `React.createElement` / JSX |
| `js/components/*.js` | Écrans, reçoivent des `props` | Composants |
| `js/main.js` | Point d'entrée, branche le store au rendu | `ReactDOM.createRoot` |
