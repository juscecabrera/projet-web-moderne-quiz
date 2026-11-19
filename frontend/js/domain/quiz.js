(function (Quiz) {
  const ACTION_TYPES = {
    CATEGORIES_LOADED: 'quiz/categoriesLoaded',
    CATEGORY_SELECTED: 'quiz/categorySelected',
    START_REQUESTED: 'quiz/startRequested',
    START_SUCCEEDED: 'quiz/startSucceeded',
    ANSWER_REQUESTED: 'quiz/answerRequested',
    ANSWER_SUCCEEDED: 'quiz/answerSucceeded',
    NEXT: 'quiz/next',
    RESTART: 'quiz/restart',
    FAILED: 'quiz/failed',
  };

  const initialQuizState = {
    status: 'idle',
    categories: [],
    categorieId: null,
    questions: [],
    currentIndex: 0,
    answers: [],
    pending: false,
    erreur: null,
  };

  function getCurrentQuestion(state) {
    return state.questions[state.currentIndex];
  }

  function getCurrentAnswer(state) {
    return state.answers[state.currentIndex];
  }

  function getQuizResults(state) {
    let score = 0;
    let bonnesReponses = 0;
    let sommeDifficultes = 0;

    state.answers.forEach((answer, index) => {
      const difficulte = Quiz.getDifficulte(state.questions[index].difficulteId);
      sommeDifficultes += difficulte.id;

      if (answer.estCorrecte) {
        bonnesReponses += 1;
        score += difficulte.multiplicateur;
      }
    });

    const total = state.answers.length;

    return {
      score,
      bonnesReponses,
      total,
      difficulteMoyenne: total > 0 ? sommeDifficultes / total : null,
    };
  }

  const actions = {
    categoriesLoaded: (categories) => ({ type: ACTION_TYPES.CATEGORIES_LOADED, categories }),
    selectCategory: (categorieId) => ({ type: ACTION_TYPES.CATEGORY_SELECTED, categorieId }),
    startRequested: () => ({ type: ACTION_TYPES.START_REQUESTED }),
    startSucceeded: (questions) => ({ type: ACTION_TYPES.START_SUCCEEDED, questions }),
    answerRequested: () => ({ type: ACTION_TYPES.ANSWER_REQUESTED }),
    answerSucceeded: (correction) => ({ type: ACTION_TYPES.ANSWER_SUCCEEDED, correction }),
    nextQuestion: () => ({ type: ACTION_TYPES.NEXT }),
    restartQuiz: () => ({ type: ACTION_TYPES.RESTART }),
    requestFailed: (message) => ({ type: ACTION_TYPES.FAILED, message }),
  };

  function quizReducer(state, action) {
    switch (action.type) {
      case ACTION_TYPES.CATEGORIES_LOADED:
        return { ...state, categories: action.categories };

      case ACTION_TYPES.CATEGORY_SELECTED:
        return { ...state, categorieId: action.categorieId };

      case ACTION_TYPES.START_REQUESTED:
        return { ...state, status: 'loading', erreur: null };

      case ACTION_TYPES.START_SUCCEEDED:
        return {
          ...state,
          status: 'playing',
          questions: action.questions,
          currentIndex: 0,
          answers: [],
          pending: false,
        };

      case ACTION_TYPES.ANSWER_REQUESTED:
        if (state.status !== 'playing' || state.pending || getCurrentAnswer(state)) {
          return state;
        }

        return { ...state, pending: true };

      case ACTION_TYPES.ANSWER_SUCCEEDED:
        return { ...state, pending: false, answers: [...state.answers, action.correction] };

      case ACTION_TYPES.NEXT: {
        if (state.status !== 'playing' || !getCurrentAnswer(state)) {
          return state;
        }

        const isLastQuestion = state.currentIndex === state.questions.length - 1;

        if (isLastQuestion) {
          return { ...state, status: 'finished' };
        }

        return { ...state, currentIndex: state.currentIndex + 1 };
      }

      case ACTION_TYPES.RESTART:
        return { ...initialQuizState, categories: state.categories };

      case ACTION_TYPES.FAILED:
        return { ...state, status: 'error', pending: false, erreur: action.message };

      default:
        return state;
    }
  }

  Quiz.initialQuizState = initialQuizState;
  Quiz.quizReducer = quizReducer;
  Quiz.getCurrentQuestion = getCurrentQuestion;
  Quiz.getCurrentAnswer = getCurrentAnswer;
  Quiz.getQuizResults = getQuizResults;
  Quiz.actions = actions;
})(window.Quiz);
