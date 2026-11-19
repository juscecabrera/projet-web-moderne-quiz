(function (Quiz) {
  Quiz.QUIZ_CONFIG = {
    tailleDePartie: 10,
  };

  Quiz.DIFFICULTES = [
    { id: 1, libelle: 'Facile', multiplicateur: 1 },
    { id: 2, libelle: 'Intermédiaire', multiplicateur: 1.5 },
    { id: 3, libelle: 'Difficile', multiplicateur: 2 },
    { id: 4, libelle: 'Expert', multiplicateur: 3 },
    { id: 5, libelle: 'Master', multiplicateur: 5 },
  ];

  Quiz.getDifficulte = function getDifficulte(id) {
    return Quiz.DIFFICULTES.find((difficulte) => difficulte.id === id);
  };
})(window.Quiz);
