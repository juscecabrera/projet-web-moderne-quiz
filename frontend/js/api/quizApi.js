(function (Quiz) {
  // Simule l'API du backend. Pour migrer : remplacer le corps de chaque fonction
  // par un fetch() vers le backend, en gardant le même nom et les mêmes retours.

  async function getCategories() {
    return Quiz.CATEGORIES.map(({ id, nom, description }) => ({ id, nom, description }));
  }

  function getQuestionsMelangees(questions, taille) {
    const questionsParDifficulte = new Map(
      Quiz.DIFFICULTES.map(({ id }) => [id, []]),
    );

    questions.forEach((question) => {
      if (!questionsParDifficulte.has(question.difficulteId)) {
        questionsParDifficulte.set(question.difficulteId, []);
      }

      questionsParDifficulte.get(question.difficulteId).push(question);
    });

    const questionsMelangees = [];

    while (questionsMelangees.length < taille) {
      let aAjouteUneQuestion = false;

      questionsParDifficulte.forEach((questionsDeMemeDifficulte) => {
        if (questionsMelangees.length < taille && questionsDeMemeDifficulte.length > 0) {
          questionsMelangees.push(questionsDeMemeDifficulte.shift());
          aAjouteUneQuestion = true;
        }
      });

      if (!aAjouteUneQuestion) {
        break;
      }
    }

    return questionsMelangees;
  }

  function getQuestionsDisponibles(categorieId, difficulteIds) {
    return Quiz.QUESTIONS.filter(
      (question) =>
        (categorieId === null || question.categorieId === categorieId) &&
        difficulteIds.includes(question.difficulteId),
    );
  }

  // Les bonnes réponses ne sont jamais envoyées ici (README racine, section 8).
  async function getPartie(categorieId, difficulteIds = Quiz.DIFFICULTES.map(({ id }) => id)) {
    return getQuestionsMelangees(
      getQuestionsDisponibles(categorieId, difficulteIds),
      Quiz.QUIZ_CONFIG.tailleDePartie,
    )
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

  function getNombreQuestionsDisponibles(categorieId, difficulteIds) {
    return getQuestionsDisponibles(categorieId, difficulteIds).length;
  }

  Quiz.api = { getCategories, getPartie, getNombreQuestionsDisponibles, validerReponse };
})(window.Quiz);
