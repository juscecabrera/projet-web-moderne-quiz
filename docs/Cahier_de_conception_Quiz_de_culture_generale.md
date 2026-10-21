# **Projet — Quiz de culture générale gamifié** 

_Cahier de conception complet — de l’idée au projet final_ 

Version de travail — 21 octobre 2026 

## **1. Vision du projet** 

Le projet consiste à créer une application web de quiz de connaissances transformée en véritable jeu. Le joueur ne se contente pas de répondre à des questions : il progresse, gagne de l’XP, améliore ses performances, débloque des récompenses, se compare aux autres et, à terme, participe à des duels, ligues, événements et modes de jeu variés. 

Boucle centrale : Jouer → répondre → gagner des points/XP → progresser → débloquer → comparer → revenir jouer. 

## **2. Principe de développement en trois étapes** 

### **POC — Proof of Concept** 

Prouver que le cœur du gameplay fonctionne et donne envie de rejouer. Le POC reste volontairement petit. 

### **MVP — Minimum Viable Product** 

Transformer le cœur validé en véritable produit utilisable avec comptes, progression, rétention, statistiques et premiers éléments sociaux. 

### **Projet final** 

Construire l’écosystème complet : compétition avancée, monde, collections, modes spéciaux, ligues, tournois, événements et personnalisation intelligente. 

## **3. POC — Proof of Concept** 

Objectif : tester une seule hypothèse majeure : « questions + difficulté + scoring + progression de performance = gameplay intéressant ». 

### **US-POC-01 — Jouer à un quiz** 

En tant que joueur, je veux lancer un quiz afin de répondre à une série de questions de culture générale. 

### **US-POC-02 — Choisir une catégorie** 

En tant que joueur, je veux choisir une catégorie afin de jouer dans un domaine qui m’intéresse. 

### **US-POC-03 — Jouer avec plusieurs difficultés** 

En tant que joueur, je veux rencontrer différents niveaux de difficulté afin que le jeu puisse mesurer mon niveau. 

### **US-POC-04 — Récompenser la difficulté** 

En tant que joueur, je veux qu’une question difficile rapporte davantage de points afin que la prise de risque ait un intérêt. 

### **US-POC-05 — Voir mon résultat** 

En tant que joueur, je veux voir mon score final, mon nombre de bonnes réponses et la difficulté moyenne. 

### **US-POC-06 — Voir la correction** 

En tant que joueur, je veux voir la bonne réponse et une explication après une question. 

### **US-POC-07 — Voir mes performances** 

En tant que joueur, je veux voir mes performances par catégorie afin d’identifier mes points forts et faibles. 

## **4. Règles du gameplay du POC** 

Proposition initiale de difficulté et de multiplicateurs : 

|Difficulté|Multiplicateur indicatif|Rôle|
|---|---|---|
|f<br>Facile|×1|Questions accessibles|
|Intermédiaire|×1,5|Niveau standard|
|Difficile|×2|Connaissances solides|
|f<br>Expert|×3|Connaissances spécialisées|
|Master|×5|Questions très difficiles|



Ces valeurs sont des paramètres de départ, pas des règles définitives. Le POC doit servir à 

observer si les écarts de récompense produisent un comportement intéressant. 

## **5. Exemple de partie POC** 

1. Le joueur sélectionne « Géographie ». 

2. Il lance 10 questions. 

3. Les questions mélangent plusieurs niveaux de difficulté. 

4. Une réponse est donnée. 

5. L’application indique immédiatement si elle est correcte. 

6. Le joueur reçoit les points correspondant à la difficulté. 

7. Une correction ou explication apparaît. 

8. À la fin, l’application affiche le score, les bonnes réponses, les erreurs et les performances par difficulté/catégorie. 

## **6. Critères de validation du POC** 

- Le joueur comprend immédiatement comment répondre. 

- La différence entre les niveaux de difficulté est compréhensible. 

- Le scoring donne envie de tenter des questions plus difficiles. 

- Le résultat final est lisible. 

- Le joueur peut identifier ses erreurs. 

- La boucle est suffisamment intéressante pour avoir envie de refaire une partie. 

## **7. Ce qui est volontairement exclu du POC** 

- Comptes et authentification avancée 

- Classement mondial 

- 1 VS 1 

- Ligues 

- Tournois 

- Avatar et boutique 

- Passeport et carte du monde 

- Cartes à collectionner 

- Événements saisonniers 

- Système social complexe 

- IA adaptative avancée 

## **8. MVP — Minimum Viable Product** 

Objectif : conserver le gameplay validé et ajouter suffisamment de progression et de rétention pour que l’application devienne un vrai produit. 

### **Comptes et profil** 

#### **US-MVP-01 — Créer un compte** 

En tant que joueur, je veux sauvegarder sa progression. 

#### **US-MVP-02 — Gagner de l’XP** 

En tant que joueur, je veux progresser grâce aux parties. 

#### **US-MVP-03 — Monter de niveau** 

En tant que joueur, je veux visualiser sa progression. 

#### **US-MVP-04 — Voir son profil** 

En tant que joueur, je veux consulter niveau, statistiques et performances. 

### **Classement** 

#### **US-MVP-05 — Consulter le classement** 

En tant que joueur, je veux comparer ses performances aux autres joueurs. 

### **Rétention** 

#### **US-MVP-06 — Streak** 

En tant que joueur, je veux conserver une série de jours consécutifs. 

#### **US-MVP-07 — Daily Challenge** 

En tant que joueur, je veux jouer chaque jour à un défi commun. 

#### **US-MVP-08 — Quêtes quotidiennes** 

En tant que joueur, je veux accomplir des objectifs précis. 

### **Apprentissage** 

#### **US-MVP-09 — Revoir mes erreurs** 

En tant que joueur, je veux retrouver ses mauvaises réponses. 

#### **US-MVP-10 — Rejouer mes erreurs** 

En tant que joueur, je veux vérifier si l’on a progressé. 

#### **US-MVP-11 — Forces et faiblesses** 

En tant que joueur, je veux identifier les catégories à améliorer. 

### **Gamification** 

#### **US-MVP-12 — Avatar** 

En tant que joueur, je veux avoir une identité visuelle. 

#### **US-MVP-13 — Badges** 

En tant que joueur, je veux collectionner les accomplissements. 

#### **US-MVP-14 — Jokers** 

En tant que joueur, je veux aider le joueur pendant une partie. 

### **Compétition — extension du MVP** 

#### **US-MVP-15 — Défier un joueur** 

En tant que joueur, je veux lancer un duel. 

#### **US-MVP-16 — Matchmaking** 

En tant que joueur, je veux trouver un adversaire de niveau comparable. 

#### **US-MVP-17 — Duel** 

En tant que joueur, je veux répondre aux mêmes questions. 

#### **US-MVP-18 — Rating** 

En tant que joueur, je veux séparer le niveau compétitif de l’XP. 

## **9. Distinction des scores** 

|Système|But|Évolution|
|---|---|---|
|XP|Progressionpermanente|Ne diminuepas|
|Rating|Compétition / 1v1|Monte ou descend selon les<br>résultats|
|Score de partie|Performance ponctuelle|Recommence à chaque<br>partie|
|Score hebdomadaire|Classement de la période|Se renouvelle chaque<br>semaine|
|Coins|Économie / récompenses|Gagnés et dépensés|



## **10. Projet final** 

Le projet final reprend le noyau du MVP et ajoute des systèmes qui augmentent la profondeur, la compétition, la collection, l’exploration et la variété des parties. 

### **Exploration mondiale** 

#### **US-FINAL-01 — Explorer le monde** 

En tant que joueur, je veux explorer le monde afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-02 — Débloquer les pays** 

En tant que joueur, je veux débloquer les pays afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-03 — Obtenir des tampons** 

En tant que joueur, je veux obtenir des tampons afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-04 — Compléter son passeport** 

En tant que joueur, je veux compléter son passeport afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-05 — Découvrir des informations sur les pays** 

En tant que joueur, je veux découvrir des informations sur les pays afin d’enrichir mon expérience et ma progression. 

### **Collections** 

#### **US-FINAL-06 — Collectionner des cartes** 

En tant que joueur, je veux collectionner des cartes afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-07 — Compléter des collections** 

En tant que joueur, je veux compléter des collections afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-08 — Obtenir des cartes rares** 

En tant que joueur, je veux obtenir des cartes rares afin d’enrichir mon expérience et ma progression. 

### **Modes de jeu** 

#### **US-FINAL-09 — Mode Globe** 

En tant que joueur, je veux mode globe afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-10 — Mode Histoire** 

En tant que joueur, je veux mode histoire afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-11 — Mode Wanted** 

En tant que joueur, je veux mode wanted afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-12 — Mode Zoom** 

En tant que joueur, je veux mode zoom afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-13 — Mode Audio** 

En tant que joueur, je veux mode audio afin d’enrichir mon expérience et ma progression. 

### **Compétition avancée** 

#### **US-FINAL-14 — Affronter le Ghost d’un joueur** 

En tant que joueur, je veux affronter le ghost d’un joueur afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-15 — Rejoindre une ligue** 

En tant que joueur, je veux rejoindre une ligue afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-16 — Monter de ligue** 

En tant que joueur, je veux monter de ligue afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-17 — Descendre de ligue** 

En tant que joueur, je veux descendre de ligue afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-18 — Participer à un tournoi** 

En tant que joueur, je veux participer à un tournoi afin d’enrichir mon expérience et ma progression. 

### **Événements** 

#### **US-FINAL-19 — Participer aux événements saisonniers** 

En tant que joueur, je veux participer aux événements saisonniers afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-20 — Obtenir des récompenses limitées** 

En tant que joueur, je veux obtenir des récompenses limitées afin d’enrichir mon expérience et ma progression. 

### **IA et personnalisation** 

#### **US-FINAL-21 — Difficulté dynamique** 

En tant que joueur, je veux difficulté dynamique afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-22 — Quiz personnalisé** 

En tant que joueur, je veux quiz personnalisé afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-23 — Recommandations** 

En tant que joueur, je veux recommandations afin d’enrichir mon expérience et ma progression. 

#### **US-FINAL-24 — Questions adaptatives** 

En tant que joueur, je veux questions adaptatives afin d’enrichir mon expérience et ma progression. 

## **11. Catalogue des fonctionnalités issues de l’alphabet A-Z** 

|Lettre|Fonctionnalité|Phase|
|---|---|---|
|A|Avatars|MVP|
|B|Badges|MVP|
|C|Cartes à collectionner|Final|
|D|DailyChallenge|MVP|
|E|Événements saisonniers|Final|
|F|Ghost|Final|
|G|Globe interactif|Final|
|H|Histoire / classement<br>chronologique|Final|
|I|Indicesprogressifs|Final|
|J|Jokers|MVP|
|K|Kilomètres / voyage virtuel|Final|
|L|Ligues hebdomadaires|Final|
|M|Mascotte|MVP+|
|N|Master levels / titres|MVP → Final|
|O|Oops / révision des erreurs|MVP|
|P|Passeport|Final|
|Q|Quêtesquotidiennes|MVP|
|R|Roue de récompense|MVP+|
|S|Streaks|MVP|
|T|Tournois|Final|
|U|Univers thématiques|Final|
|V|Versus 1v1|MVP+ → Final|
|W|Wanted|Final|



|X|XP|MVP|
|---|---|---|
|Y|Mode audio|Final|
|Z|Zoomprogressif|Final|



## **12. Parcours utilisateur cible** 

Parcours type dans la version mature : 

#### 9. Connexion 

10. Accueil avec progression, streak et quêtes 

11. Choix d’un quiz ou d’un défi recommandé 

12. Questions adaptées au niveau du joueur 

13. Points + XP + récompenses 

14. Correction des erreurs 

15. Résultat et statistiques 

16. Progression de niveau / badge / carte 

17. Mise à jour des classements 

18. Possibilité de lancer un 1v1 ou de continuer une quête 

19. Retour quotidien via Daily Challenge, streak ou événement 

## **13. Architecture fonctionnelle globale** 

Le système final peut être pensé comme plusieurs modules indépendants : 

- Moteur de questions : questions, réponses, catégories, difficulté, sources, explications. 

- Moteur de partie : sélection des questions, chronomètre, réponses, score. 

- Moteur de progression : XP, niveaux, maîtrise. 

- Moteur compétitif : rating, matchmaking, 1v1, ligues. 

- Moteur de récompenses : badges, coins, cartes, accessoires. 

- Moteur de rétention : streaks, Daily Challenge, quêtes, événements. 

- Moteur d’apprentissage : erreurs, recommandations, difficulté dynamique. 

- Moteur social : profils, classements, défis, Ghost. 

- Moteur de contenu : création, validation, catégorisation et renouvellement des questions. 

## **14. Données importantes pour les questions** 

- Question 

- Réponses possibles 

- Réponse correcte 

- Catégorie 

- Sous-catégorie 

- Difficulté initiale 

- Difficulté calculée à partir des statistiques 

- Explication 

- Source 

- Langue 

- Type de question 

- Date de création 

- Nombre de parties 

- Taux de réussite 

- Temps moyen de réponse 

- Qualité / statut de validation 

## **15. Difficulté dynamique** 

La difficulté ne doit pas rester uniquement une étiquette définie manuellement. À terme, les statistiques réelles des joueurs peuvent recalibrer les questions. Une question réussie par 95 % des joueurs est probablement plus facile qu’une question réussie par 12 %. Le temps de réponse, le niveau des joueurs et la catégorie doivent également être pris en compte. 

Le système peut ainsi distinguer difficulté théorique et difficulté observée. 

## **16. Principes de scoring** 

- La difficulté doit avoir un poids important. 

- La vitesse peut apporter un bonus, mais ne doit pas écraser la connaissance. 

- Les jokers doivent avoir un coût ou une limitation pour éviter les abus. 

- Les scores compétitifs doivent être séparés de l’XP. 

- Le système doit éviter qu’un joueur choisissant uniquement des questions faciles domine artificiellement le classement. 

- Les formules doivent être testées avec des simulations avant déploiement. 

## **17. Priorisation** 

|Priorité|Contenu|Pourquoi|Phase|
|---|---|---|---|
|P0|Quiz + catégories +<br>difficultés + score +<br>correction|Cœur du produit|POC|
|P1|Compte + XP +<br>niveau + stats|Progression<br>persistante|MVP|
|P1|Classement + Daily +<br>Streak|Rétention /<br>compétition légère|MVP|
|P1|Erreurs + quêtes +<br>badges +jokers|Apprentissage et<br>gamification|MVP|
|P2|1v1 + matchmaking +<br>Rating|Compétition forte|MVP+|
|P3|Ghost + ligues +<br>tournois|Profondeur<br>compétitive|Final|
|P3|Monde + passeport +<br>collections|Progression longue|Final|
|P3|Modes spéciaux +|Variété|Final|



||événements|||
|---|---|---|---|
|P3|IA adaptative|Personnalisation|Final|
|||avancée||



## **18. Règle de développement** 

Ne pas construire la version finale dès le début. Chaque phase doit être un filtre : 

- POC : le gameplay fonctionne-t-il ? 

- MVP : les joueurs ont-ils une raison de revenir ? 

- MVP+ : la compétition crée-t-elle une vraie motivation ? 

- Final : les systèmes supplémentaires renforcent-ils réellement le jeu ou ajoutent-ils seulement de la complexité ? 

## **19. Résumé en une page** 

POC = Jeu de quiz minimal avec catégories, difficultés, score, correction et statistiques simples. 

MVP = POC + comptes + XP + niveaux + profils + classement + Daily Challenge + streak + quêtes + badges + jokers + révision des erreurs. 

MVP+ = ajout du 1v1, matchmaking et Rating. 

Projet final = compétition avancée + Ghost + ligues + tournois + exploration du monde + passeport + collections + modes spéciaux + événements + personnalisation et difficulté adaptatives par IA. 

## **20. Questions à valider avant de coder le POC** 

- Combien de questions dans une partie ? 

- Le joueur choisit-il la difficulté ou est-elle mélangée automatiquement ? 

- Le temps de réponse influence-t-il le score ? 

- Les questions difficiles doivent-elles être accessibles immédiatement ? 

- Quel système de points donne le meilleur sentiment de progression ? 

- Faut-il afficher l’explication immédiatement ou uniquement à la fin ? 

- Quelle première catégorie contient suffisamment de questions de qualité pour tester le concept ? 

- Comment valider les questions générées ou préparées ? 

