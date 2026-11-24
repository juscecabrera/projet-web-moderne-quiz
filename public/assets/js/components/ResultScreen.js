(function (Quiz) {
  const h = Quiz.h;
  const formatNombre = new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 2 });

  function StatsList({ items }) {
    return h(
      'ul',
      { className: 'stats-list' },
      items.map((item) =>
        h(
          'li',
          null,
          h('div', { className: 'stats-ligne' }, h('span', null, item.label), h('strong', null, `${item.taux} %`)),
          h('div', { className: 'stats-barre' }, h('span', { style: `width: ${item.taux}%` })),
        ),
      ),
    );
  }

  Quiz.ResultScreen = function ResultScreen({ results, statistiques, onRestart }) {
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

      h('h2', { className: 'section-title' }, 'Mes performances par catégorie'),
      statistiques.pointFort &&
        h(
          'p',
          { className: 'stats-point stats-point--fort' },
          `Point fort : ${statistiques.pointFort.label} (${statistiques.pointFort.taux} %)`,
        ),
      statistiques.pointFaible &&
        h(
          'p',
          { className: 'stats-point stats-point--faible' },
          `Point faible : ${statistiques.pointFaible.label} (${statistiques.pointFaible.taux} %)`,
        ),
      StatsList({ items: statistiques.parCategorie }),

      h('h2', { className: 'section-title' }, 'Mes performances par difficulté'),
      StatsList({ items: statistiques.parDifficulte }),

      h('button', { type: 'button', className: 'btn btn-primary', onClick: onRestart }, 'Rejouer'),
    );
  };
})(window.Quiz);
