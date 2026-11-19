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

  Quiz.HomeScreen = function HomeScreen({ categories, categorieId, onSelectCategory, onStart }) {
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
      h(
        'ul',
        { className: 'difficultes' },
        Quiz.DIFFICULTES.map((difficulte) => h('li', null, Quiz.DifficultyBadge({ difficulte }))),
      ),

      h(
        'button',
        { type: 'button', className: 'btn btn-primary', onClick: () => onStart(categorieId) },
        'Lancer le quiz',
      ),
    );
  };
})(window.Quiz);
