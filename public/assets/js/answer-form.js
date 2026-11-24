const answerForm = document.querySelector('[data-answer-form]');

if (answerForm) {
  const submit = answerForm.querySelector('button[type="submit"]');
  const sync = () => {
    submit.disabled = !answerForm.querySelector('input[name="reponse_id"]:checked');
  };
  answerForm.addEventListener('change', sync);
  window.addEventListener('pageshow', sync);
  sync();
}
