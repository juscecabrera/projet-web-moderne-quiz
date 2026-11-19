(function (Quiz) {
  const h = Quiz.h;
  const formatNombre = new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 2 });

  Quiz.ResultScreen = function ResultScreen({ results, onRestart }) {
    const { score, bonnesReponses, total, difficulteMoyenne } = results;
    const niveauMax = Quiz.DIFFICULTES[Quiz.DIFFICULTES.length - 1].id;

    return h(
      'section',
      { className: 'screen result-screen' },
      h('h1', null, 'Quiz terminé'),
      h(
        'dl',
        { className: 'result-stats' },
        h('div', null, h('dt', null, 'Score final'), h('dd', null, `${formatNombre.format(score)} pts`)),
        h('div', null, h('dt', null, 'Bonnes réponses'), h('dd', null, `${bonnesReponses} / ${total}`)),
        h(
          'div',
          null,
          h('dt', null, 'Difficulté moyenne'),
          h('dd', null, difficulteMoyenne === null ? '—' : `${formatNombre.format(difficulteMoyenne)} / ${niveauMax}`),
        ),
      ),
      h('button', { type: 'button', className: 'btn btn-primary', onClick: onRestart }, 'Rejouer'),
    );
  };
})(window.Quiz);
