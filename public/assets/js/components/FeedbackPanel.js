(function (Quiz) {
  const h = Quiz.h;

  Quiz.FeedbackPanel = function FeedbackPanel({ answer }) {
    return h(
      'div',
      { className: answer.estCorrecte ? 'feedback feedback--correct' : 'feedback feedback--wrong' },
      h('p', { className: 'feedback-titre' }, answer.estCorrecte ? 'Bonne réponse !' : 'Mauvaise réponse'),
      h('p', null, `Réponse correcte : ${answer.bonneReponseTexte}`),
      h('p', null, answer.explication),
    );
  };
})(window.Quiz);
