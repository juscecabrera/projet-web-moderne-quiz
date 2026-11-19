import { h } from '../core/h.js';

export function StartScreen({ onStart }) {
  return h(
    'section',
    { className: 'screen start-screen' },
    h('h1', null, 'Quiz de culture générale'),
    h('p', null, 'Répondez à 10 questions de culture générale. Une seule réponse est possible par question.'),
    h('button', { type: 'button', className: 'btn btn-primary', onClick: onStart }, 'Lancer le quiz'),
  );
}
