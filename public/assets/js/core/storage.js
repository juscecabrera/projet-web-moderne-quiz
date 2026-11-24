(function (Quiz) {
  const CLE_HISTORIQUE = 'quiz.historique';

  Quiz.loadHistorique = function loadHistorique() {
    try {
      return JSON.parse(localStorage.getItem(CLE_HISTORIQUE)) ?? [];
    } catch {
      return [];
    }
  };

  Quiz.saveHistorique = function saveHistorique(historique) {
    try {
      localStorage.setItem(CLE_HISTORIQUE, JSON.stringify(historique));
    } catch {
      return;
    }
  };
})(window.Quiz);
