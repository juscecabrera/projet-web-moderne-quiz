(function (Quiz) {
  const h = Quiz.h;

  Quiz.DifficultyBadge = function DifficultyBadge({ difficulte }) {
    return h('span', { className: `badge badge--${difficulte.id}` }, difficulte.libelle);
  };
})(window.Quiz);
