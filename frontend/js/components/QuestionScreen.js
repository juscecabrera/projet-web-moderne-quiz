import { h } from '../core/h.js';

export function QuestionScreen({ question, index, total, selectedReponseId, onAnswer, onNext }) {
  const answered = selectedReponseId !== undefined;
  const isLastQuestion = index === total - 1;

  return h(
    'section',
    { className: 'screen question-screen' },
    h('p', { className: 'progress' }, `Question ${index + 1} / ${total}`),
    h('h2', { className: 'question' }, question.enonce),
    h(
      'ul',
      { className: 'answers' },
      question.reponses.map((reponse) =>
        h(
          'li',
          null,
          h(
            'button',
            {
              type: 'button',
              className: reponse.id === selectedReponseId ? 'answer answer--selected' : 'answer',
              disabled: answered,
              onClick: () => onAnswer(reponse.id),
            },
            reponse.texte,
          ),
        ),
      ),
    ),
    answered &&
      h(
        'button',
        { type: 'button', className: 'btn btn-primary', onClick: onNext },
        isLastQuestion ? 'Terminer le quiz' : 'Question suivante',
      ),
  );
}
