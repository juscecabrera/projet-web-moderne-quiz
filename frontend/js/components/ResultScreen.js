(function (Quiz) {
  const h = Quiz.h;

  Quiz.ResultScreen = function ResultScreen({ onRestart }) {
    return h(
      'section',
      { className: 'screen result-screen' },
      h('h1', null, 'Quiz terminé'),
      h('p', null, 'Vous avez répondu à toutes les questions.'),
      h('button', { type: 'button', className: 'btn btn-primary', onClick: onRestart }, 'Rejouer'),
    );
  };
})(window.Quiz);
