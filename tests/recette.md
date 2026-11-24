# Recette du quiz PHP

Sur le deuxième poste, partir de `develop` après fusion, puis installer la base et démarrer le serveur avec le README. Utiliser deux comptes de test distincts.

| Lot | Contrôle attendu |
| --- | --- |
| 1A | Refuser une confirmation différente. Inscrire un joueur et arriver directement à l’accueil. Se déconnecter, vérifier la protection de `partie.php`, puis essayer l’e-mail en double, un mauvais mot de passe et une connexion réussie. |
| 2B | Depuis **Jouer**, lancer Sciences et Géographie et vérifier que leur URL et leurs questions correspondent. Sur une base de test où l’un des deux thèmes n’a aucune question validée, vérifier que l’autre reste jouable. Refaire le contrôle avec les deux thèmes indisponibles. |
| 3C | Avec `QUIZ_QUESTION_LIMIT=1`, jouer une partie correcte et une incorrecte. Vérifier les quatre choix, la difficulté, les points et **Voir mon bilan**. Soumettre deux fois la même réponse puis actualiser. Vérifier qu’une seule réponse est enregistrée par partie. Avec la limite à 10, vérifier **Question suivante** et terminer la partie. |
| 4D | Vérifier l’état vide de **Mes performances** avant de jouer, puis l’historique et les liens après une partie. Comparer le bilan à MySQL. Avec le deuxième compte, ouvrir l’URL du bilan du premier compte et vérifier que l’accès est refusé. |

Pour le profil et le classement, enregistrer un pseudo public, se reconnecter et vérifier sa persistance. Vérifier qu’un deuxième compte se voit refuser le même pseudo. Jouer deux parties de dix questions avec des taux différents dans un thème et obtenir le même meilleur taux avec un deuxième compte. Vérifier les rangs identiques et une seule ligne par compte. Vérifier le cas vide d’un autre thème, la présence du taux dans les performances filtrées et le lien vers le bon classement depuis le bilan. Abandonner une partie après une réponse et vérifier dans MySQL le statut `abandonnee` et la présence de cette réponse.

```sql
SELECT p.id_partie, p.statut, p.score_total, p.nombre_bonnes_reponses,
       COUNT(r.id_question) AS reponses, SUM(r.points_obtenus) AS points_enregistres
FROM parties p
JOIN reponses_joueurs r ON r.id_partie = p.id_partie
GROUP BY p.id_partie;
```
