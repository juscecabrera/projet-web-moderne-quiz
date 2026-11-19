(function (Quiz) {
  const h = Quiz.h;

  Quiz.App = function App({
    state,
    nombreQuestionsDisponibles,
    onSelectCategory,
    onSelectDifficulty,
    onStart,
    onAnswer,
    onNext,
    onRestart,
  }) {
    if (state.status === 'loading') {
      return h('section', { className: 'screen' }, h('p', { className: 'loading' }, 'Chargement des questions…'));
    }

    if (state.status === 'error') {
      return h(
        'section',
        { className: 'screen' },
        h('h1', null, 'Une erreur est survenue'),
        h('p', null, state.erreur),
        h('button', { type: 'button', className: 'btn btn-primary', onClick: onRestart }, "Retour à l'accueil"),
      );
    }

    if (state.status === 'playing') {
      return Quiz.QuestionScreen({
        question: Quiz.getCurrentQuestion(state),
        index: state.currentIndex,
        total: state.questions.length,
        answer: Quiz.getCurrentAnswer(state),
        pending: state.pending,
        onAnswer,
        onNext,
      });
    }

    if (state.status === 'finished') {
      return Quiz.ResultScreen({
        results: Quiz.getQuizResults(state),
        statistiques: Quiz.getStatistiques(state.historique, state.categories),
        onRestart,
      });
    }

    return Quiz.HomeScreen({
      categories: state.categories,
      categorieId: state.categorieId,
      difficulteIds: state.difficulteIds,
      nombreQuestionsDisponibles,
      onSelectCategory,
      onSelectDifficulty,
      onStart,
    });
  };
})(window.Quiz);
