import { StartScreen } from './StartScreen.js';
import { QuestionScreen } from './QuestionScreen.js';
import { EndScreen } from './EndScreen.js';
import { getCurrentQuestion, getSelectedReponseId } from '../domain/quiz.js';

export function App({ state, onStart, onAnswer, onNext, onRestart }) {
  if (state.status === 'playing') {
    return QuestionScreen({
      question: getCurrentQuestion(state),
      index: state.currentIndex,
      total: state.questions.length,
      selectedReponseId: getSelectedReponseId(state),
      onAnswer,
      onNext,
    });
  }

  if (state.status === 'finished') {
    return EndScreen({ onRestart });
  }

  return StartScreen({ onStart });
}
