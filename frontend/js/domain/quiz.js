export const QUIZ_SIZE = 10;

export const ACTION_TYPES = {
  START: 'quiz/start',
  ANSWER: 'quiz/answer',
  NEXT: 'quiz/next',
  RESTART: 'quiz/restart',
};

export const initialQuizState = {
  status: 'idle',
  questions: [],
  currentIndex: 0,
  answers: [],
};

export function shuffle(items, random = Math.random) {
  const copy = [...items];

  for (let i = copy.length - 1; i > 0; i--) {
    const j = Math.floor(random() * (i + 1));
    [copy[i], copy[j]] = [copy[j], copy[i]];
  }

  return copy;
}

export function toPlayerQuestion(question) {
  return {
    id: question.id,
    enonce: question.enonce,
    explication: question.explication,
    categorieId: question.categorieId,
    difficulteId: question.difficulteId,
    reponses: question.reponses.map(({ id, texte }) => ({ id, texte })),
  };
}

export function buildQuiz(questions, size = QUIZ_SIZE, random = Math.random) {
  return shuffle(questions, random).slice(0, size).map(toPlayerQuestion);
}

export function getCurrentQuestion(state) {
  return state.questions[state.currentIndex];
}

export function hasAnswered(state) {
  return state.answers.length > state.currentIndex;
}

export function getSelectedReponseId(state) {
  return state.answers[state.currentIndex]?.reponseId;
}

export function startQuiz(questions) {
  return { type: ACTION_TYPES.START, questions };
}

export function answerQuestion(reponseId) {
  return { type: ACTION_TYPES.ANSWER, reponseId };
}

export function nextQuestion() {
  return { type: ACTION_TYPES.NEXT };
}

export function restartQuiz() {
  return { type: ACTION_TYPES.RESTART };
}

export function quizReducer(state, action) {
  switch (action.type) {
    case ACTION_TYPES.START:
      return {
        status: 'playing',
        questions: action.questions,
        currentIndex: 0,
        answers: [],
      };

    case ACTION_TYPES.ANSWER: {
      if (state.status !== 'playing' || hasAnswered(state)) {
        return state;
      }

      const question = getCurrentQuestion(state);
      const isValidReponse = question.reponses.some((reponse) => reponse.id === action.reponseId);

      if (!isValidReponse) {
        return state;
      }

      return {
        ...state,
        answers: [...state.answers, { questionId: question.id, reponseId: action.reponseId }],
      };
    }

    case ACTION_TYPES.NEXT: {
      if (state.status !== 'playing' || !hasAnswered(state)) {
        return state;
      }

      const isLastQuestion = state.currentIndex === state.questions.length - 1;

      if (isLastQuestion) {
        return { ...state, status: 'finished' };
      }

      return { ...state, currentIndex: state.currentIndex + 1 };
    }

    case ACTION_TYPES.RESTART:
      return initialQuizState;

    default:
      return state;
  }
}
