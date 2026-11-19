(function (Quiz) {
  const h = Quiz.h;

  function CategoryOption({ id, nom, description, selected, onSelect }) {
    return h(
      'li',
      null,
      h(
        'button',
        {
          type: 'button',
          className: selected ? 'category category--selected' : 'category',
          'aria-pressed': selected ? 'true' : 'false',
          onClick: () => onSelect(id),
        },
        h('strong', null, nom),
        h('span', null, description),
      ),
    );
  }

  Quiz.HomeScreen = function HomeScreen({
    categories,
    categorieId,
    difficulteIds,
    nombreQuestionsDisponibles,
    onSelectCategory,
    onSelectDifficulty,
    onStart,
  }) {
    const nombreQuestions = Math.min(
      nombreQuestionsDisponibles,
      Quiz.QUIZ_CONFIG.tailleDePartie,
    );
    const aideDifficulte =
      nombreQuestionsDisponibles === 0
        ? 'Aucune question ne correspond à cette catégorie et à ces niveaux.'
        : `Choisissez un niveau ou combinez-en plusieurs. ${nombreQuestions} ${
            nombreQuestions > 1 ? 'questions disponibles' : 'question disponible'
          } dans cette partie.`;

    return h(
      'section',
      { className: 'screen home-screen' },
      h('h1', null, 'Quiz de culture générale'),
      h(
        'p',
        null,
        `Une partie comporte jusqu'à ${Quiz.QUIZ_CONFIG.tailleDePartie} questions. Une seule réponse est possible par question.`,
      ),

      h('h2', { className: 'section-title' }, 'Choisissez une catégorie'),
      h(
        'ul',
        { className: 'categories' },
        CategoryOption({
          id: null,
          nom: 'Toutes les catégories',
          description: 'Questions de tous les thèmes',
          selected: categorieId === null,
          onSelect: onSelectCategory,
        }),
        categories.map((categorie) =>
          CategoryOption({
            id: categorie.id,
            nom: categorie.nom,
            description: categorie.description,
            selected: categorieId === categorie.id,
            onSelect: onSelectCategory,
          }),
        ),
      ),

      h('h2', { className: 'section-title' }, 'Niveaux de difficulté'),
      h('p', { className: 'difficulty-help' }, aideDifficulte),
      h(
        'ul',
        { className: 'difficultes' },
        Quiz.DIFFICULTES.map((difficulte) =>
          h(
            'li',
            null,
            h(
              'label',
              { className: 'difficulty-option' },
              h('input', {
                type: 'checkbox',
                checked: difficulteIds.includes(difficulte.id),
                'aria-label': difficulte.libelle,
                onChange: () => onSelectDifficulty(difficulte.id),
              }),
              Quiz.DifficultyBadge({ difficulte }),
            ),
          ),
        ),
      ),

      h(
        'button',
        {
          type: 'button',
          className: 'btn btn-primary',
          disabled: difficulteIds.length === 0 || nombreQuestionsDisponibles === 0,
          onClick: () => onStart(categorieId, difficulteIds),
        },
        'Lancer le quiz',
      ),
    );
  };
})(window.Quiz);
