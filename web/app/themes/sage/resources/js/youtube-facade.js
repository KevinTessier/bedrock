/**
 * YouTube facade: load the player only on user interaction.
 *
 * - RGPD  : no request to Google until the button is clicked.
 * - RGESN : the heavy third-party iframe is loaded on demand only.
 * - RGAA  : the facade is a real <button>; on activation the iframe is injected
 *           with a title and receives focus so screen-reader users land on it.
 */
function loadPlayer(button) {
  const id = button.dataset.ytId;
  const title = button.dataset.ytTitle || 'YouTube';

  if (!id) {
    return;
  }

  const iframe = document.createElement('iframe');
  iframe.className = 'yt-facade__iframe';
  // Cookie-less domain + autoplay (allowed: triggered by a user gesture).
  iframe.src = `https://www.youtube-nocookie.com/embed/${id}?autoplay=1&rel=0`;
  iframe.title = title;
  iframe.allow =
    'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share';
  iframe.allowFullscreen = true;
  iframe.loading = 'lazy';

  button.replaceWith(iframe);
  // RGAA: move focus to the freshly loaded content.
  iframe.focus();
}

document.addEventListener('click', (event) => {
  const button = event.target.closest('.yt-facade');
  if (button) {
    loadPlayer(button);
  }
});
