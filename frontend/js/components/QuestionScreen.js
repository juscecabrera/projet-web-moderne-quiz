(function (Quiz) {
  const h = Quiz.h;

  function getAnswerClassName(reponse, answer) {
    if (!answer) {
      return 'answer';
    }

    if (reponse.id === answer.bonneReponseId) {
      return 'answer answer--correct';
    }

    if (reponse.id === answer.reponseId) {
      return 'answer answer--wrong';
    }

    return 'answer';
  }

  Quiz.QuestionScreen = function QuestionScreen({ question, index, total, answer, pending, onAnswer, onNext }) {
    const answered = answer !== undefined;
    const isLastQuestion = index === total - 1;

    return h(
      'section',
      { className: 'screen question-screen' },
      h(
        'div',
        { className: 'question-meta' },
        h('p', { className: 'progress' }, `Question ${index + 1} / ${total}`),
        Quiz.DifficultyBadge({ difficulte: Quiz.getDifficulte(question.difficulteId) }),
      ),
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
                className: getAnswerClassName(reponse, answer),
                disabled: answered || pending,
                onClick: () => onAnswer(reponse.id),
              },
              reponse.texte,
            ),
          ),
        ),
      ),
      answered && Quiz.FeedbackPanel({ answer }),
      answered &&
        h(
          'button',
          { type: 'button', className: 'btn btn-primary', onClick: onNext },
          isLastQuestion ? 'Terminer le quiz' : 'Question suivante',
        ),
    );
  };
})(window.Quiz);
