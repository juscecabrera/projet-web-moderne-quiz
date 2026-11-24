# Flux du quiz

Les pages sont dans `public/`, le style et le JavaScript dans `public/assets/`, les fonctions PHP et la connexion PDO dans `src/`.

1. `index.php` ouvre la connexion ou l’accueil selon la session. L’inscription ouvre directement l’accueil.
2. Sur l’accueil, **Jouer** ouvre `themes.php` et **Mes performances** ouvre l’historique du compte connecté.
3. Le bouton d’un thème transmet son `theme_id` à `partie.php`. PHP crée une partie pour le `user_id` connecté, charge les questions du thème et conserve l’ordre des questions en session.
4. Après **Valider**, PHP enregistre la réponse et affiche la correction sur la même page. Le joueur décide quand passer à la suivante ou ouvrir son bilan.
5. Le bilan utilise `partie_id` et le `user_id` de la session pour charger la partie terminée. Les performances donnent accès aux bilans de ce même compte.
