import { createStore } from './core/store.js';
import {
  buildQuiz,
  initialQuizState,
  answerQuestion,
  nextQuestion,
  quizReducer,
  restartQuiz,
  startQuiz,
} from './domain/quiz.js';
import { QUESTIONS } from './data/questions.js';
import { App } from './components/App.js';

const root = document.getElementById('app');
const store = createStore(quizReducer, initialQuizState);

const handlers = {
  onStart: () => store.dispatch(startQuiz(buildQuiz(QUESTIONS))),
  onAnswer: (reponseId) => store.dispatch(answerQuestion(reponseId)),
  onNext: () => store.dispatch(nextQuestion()),
  onRestart: () => store.dispatch(restartQuiz()),
};

function renderApp(state) {
  root.replaceChildren(App({ state, ...handlers }));
}

store.subscribe(renderApp);
renderApp(store.getState());
