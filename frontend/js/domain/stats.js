(function (Quiz) {
  function pourcentage(bonnes, total) {
    return Math.round((bonnes / total) * 100);
  }

  function regrouper(reponses, cle, libelleDe) {
    const groupes = new Map();

    reponses.forEach((reponse) => {
      const id = reponse[cle];
      const groupe = groupes.get(id) ?? { id, label: libelleDe(id), total: 0, bonnes: 0 };

      groupe.total += 1;

      if (reponse.estCorrecte) {
        groupe.bonnes += 1;
      }

      groupes.set(id, groupe);
    });

    return [...groupes.values()]
      .map((groupe) => ({ ...groupe, taux: pourcentage(groupe.bonnes, groupe.total) }))
      .sort((a, b) => a.id - b.id);
  }

  Quiz.getStatistiques = function getStatistiques(historique, categories) {
    const parCategorie = regrouper(
      historique,
      'categorieId',
      (id) => categories.find((categorie) => categorie.id === id)?.nom ?? 'Catégorie inconnue',
    );
    const parDifficulte = regrouper(historique, 'difficulteId', (id) => Quiz.getDifficulte(id).libelle);
    const triees = [...parCategorie].sort((a, b) => b.taux - a.taux);
    const meilleure = triees[0];
    const moinsBonne = triees[triees.length - 1];
    const hasContraste = parCategorie.length > 1 && meilleure.taux !== moinsBonne.taux;

    return {
      parCategorie,
      parDifficulte,
      pointFort: hasContraste ? meilleure : null,
      pointFaible: hasContraste ? moinsBonne : null,
    };
  };
})(window.Quiz);
