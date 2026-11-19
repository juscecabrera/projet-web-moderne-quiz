(function (Quiz) {
  const { actions } = Quiz;
  const root = document.getElementById('app');
  const store = Quiz.createStore(Quiz.quizReducer, Quiz.initialQuizState);

  const handlers = {
    onSelectCategory: (categorieId) => store.dispatch(actions.selectCategory(categorieId)),

    onStart: async (categorieId) => {
      store.dispatch(actions.startRequested());

      try {
        store.dispatch(actions.startSucceeded(await Quiz.api.getPartie(categorieId)));
      } catch (error) {
        store.dispatch(actions.requestFailed(error.message));
      }
    },

    onAnswer: async (reponseId) => {
      if (store.getState().pending) {
        return;
      }

      const question = Quiz.getCurrentQuestion(store.getState());
      store.dispatch(actions.answerRequested());

      try {
        store.dispatch(actions.answerSucceeded(await Quiz.api.validerReponse(question.id, reponseId)));
      } catch (error) {
        store.dispatch(actions.requestFailed(error.message));
      }
    },

    onNext: () => store.dispatch(actions.nextQuestion()),
    onRestart: () => store.dispatch(actions.restartQuiz()),
  };

  function renderApp(state) {
    root.replaceChildren(Quiz.App({ state, ...handlers }));
  }

  store.subscribe(renderApp);
  renderApp(store.getState());

  Quiz.api
    .getCategories()
    .then((categories) => store.dispatch(actions.categoriesLoaded(categories)))
    .catch((error) => store.dispatch(actions.requestFailed(error.message)));
})(window.Quiz);
