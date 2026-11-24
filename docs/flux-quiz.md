# Flux du quiz

Les pages sont dans `public/`, le style et le JavaScript dans `public/assets/`, les fonctions PHP et la connexion PDO dans `src/`.

1. `index.php` ouvre la connexion ou l’accueil selon la session. L’inscription ouvre directement l’accueil.
2. Sur l’accueil, **Jouer** ouvre `themes.php` et **Mes performances** ouvre l’historique du compte connecté.
3. Le formulaire transmet `theme_id` et la difficulté à `partie.php`. PHP crée une partie pour le `user_id` connecté et conserve en session le tirage limité au thème et à la difficulté choisis.
4. Après **Valider**, PHP enregistre la réponse et affiche la correction sur la même page. Le joueur décide quand passer à la suivante ou ouvrir son bilan.
5. Le bilan utilise `partie_id` et le `user_id` de la session pour charger la partie terminée. Les performances donnent accès aux bilans de ce même compte.
6. Le profil édite le pseudo public unique du compte. Le bilan et l’historique indiquent le taux de réussite. Le classement lit les dix réponses des parties terminées pour retrouver leur thème et conserve le meilleur taux par compte. Une partie interrompue avec **Abandonner la partie** passe à `abandonnee` et conserve les réponses données.
