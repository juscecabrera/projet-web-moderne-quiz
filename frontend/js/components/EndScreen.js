import { h } from '../core/h.js';

export function EndScreen({ onRestart }) {
  return h(
    'section',
    { className: 'screen end-screen' },
    h('h1', null, 'Quiz terminé'),
    h('p', null, 'Vous avez répondu à toutes les questions.'),
    h('button', { type: 'button', className: 'btn btn-primary', onClick: onRestart }, 'Rejouer'),
  );
}
