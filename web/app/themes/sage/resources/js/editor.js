import domReady from '@wordpress/dom-ready';

// Auto-enregistre tous les blocs custom : resources/blocks/*/index.{jsx,js}
// (côté serveur, setup.php scanne les mêmes dossiers).
import.meta.glob('../blocks/*/index.{jsx,js}', { eager: true });

domReady(() => {
  //
});
