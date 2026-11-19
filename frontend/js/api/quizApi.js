(function (Quiz) {
  // Simule l'API du backend. Pour migrer : remplacer le corps de chaque fonction
  // par un fetch() vers le backend, en gardant le même nom et les mêmes retours.

  async function getCategories() {
    return Quiz.CATEGORIES.map(({ id, nom, description }) => ({ id, nom, description }));
  }

  // Les bonnes réponses ne sont jamais envoyées ici (README racine, section 8).
  async function getPartie(categorieId) {
    return Quiz.QUESTIONS.filter((question) => categorieId === null || question.categorieId === categorieId)
      .slice(0, Quiz.QUIZ_CONFIG.tailleDePartie)
      .map(({ id, enonce, categorieId: idCategorie, difficulteId, reponses }) => ({
        id,
        enonce,
        categorieId: idCategorie,
        difficulteId,
        reponses: reponses.map((reponse) => ({ id: reponse.id, texte: reponse.texte })),
      }));
  }

  async function validerReponse(questionId, reponseId) {
    const question = Quiz.QUESTIONS.find((item) => item.id === questionId);
    const bonneReponse = question.reponses.find((item) => item.estCorrecte);

    return {
      questionId,
      reponseId,
      estCorrecte: reponseId === bonneReponse.id,
      bonneReponseId: bonneReponse.id,
      bonneReponseTexte: bonneReponse.texte,
      explication: question.explication,
    };
  }

  Quiz.api = { getCategories, getPartie, validerReponse };
})(window.Quiz);
