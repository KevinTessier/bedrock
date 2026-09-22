/**
 * Styles par défaut des blocs Gutenberg, fusionnés dans le theme.json compilé.
 *
 * - `elements`  -> styles.elements (liens, boutons, titres…)
 * - `blocks`    -> styles.blocks.<core/xxx>
 *
 * Ces styles sont nécessaires car le thème déqueue le CSS des blocs core en
 * front (RGESN) : sans eux, boutons, citations, listes, légendes… sont nus.
 *
 * Note : la propriété `css` de theme.json ne gère PAS les @media (le parseur
 * WP découpe sur « } »). Les correctifs responsive (empilement des colonnes,
 * grille du media-text) vivent donc dans app.css.
 */

// Système de couleurs (cohérent avec la palette WP « beige »)
const ink = '#1f2937'; // texte fort / accents
const inkHover = '#111827'; // survol bouton
const muted = '#6b7280'; // légendes, citations
const beige = 'var:preset|color|beige'; // fond doux (référence le preset WP)
const beigeVar = 'var(--wp--preset--color--beige)'; // même couleur, pour les chaînes `css`

export default {
  // ---- styles.elements -----------------------------------------------------
  elements: {
    // Liens dans le contenu
    link: {
      color: { text: ink },
      ':hover': { typography: { textDecoration: 'underline' } },
    },

    // Tous les boutons (core/button et dérivés)
    button: {
      color: { background: ink, text: '#ffffff' },
      border: { radius: '0.375rem' },
      spacing: {
        padding: { top: '0.625rem', right: '1.25rem', bottom: '0.625rem', left: '1.25rem' },
      },
      typography: { fontWeight: '600', textDecoration: 'none' },
      ':hover': { color: { background: inkHover } },
      ':focus': { color: { background: inkHover } },
      ':active': { color: { background: inkHover } },
    },

    // Titres : graisse + interlignage communs
    heading: {
      typography: { fontWeight: '700', lineHeight: '1.2' },
    },
    // Hiérarchie de tailles (fluide, cf. settings.typography.fluid)
    h2: {
      typography: { fontSize: '1.75rem' },
      spacing: { margin: { top: '2rem', bottom: '0.75rem' } },
    },
    h3: {
      typography: { fontSize: '1.375rem' },
      spacing: { margin: { top: '1.5rem', bottom: '0.5rem' } },
    },
    h4: {
      typography: { fontSize: '1.125rem' },
      spacing: { margin: { top: '1.25rem', bottom: '0.5rem' } },
    },
  },

  // ---- styles.blocks -------------------------------------------------------
  blocks: {
    'core/paragraph': {
      spacing: { margin: { bottom: '0.5rem', top: '0.5rem' } },
      typography: { lineHeight: '1.7' },
    },

    // Listes : Tailwind preflight retire puces/marges -> on rétablit
    'core/list': {
      spacing: { margin: { top: '0', bottom: '1rem' } },
      css: 'padding-left: 1.5rem; list-style: revert; & li{ margin-bottom: 0.25rem; }',
    },

    // Citation : fond beige + filet à gauche + italique
    'core/quote': {
      color: { background: beige },
      border: { left: { color: ink, width: '4px', style: 'solid' } },
      spacing: {
        margin: { top: '1.5rem', bottom: '1.5rem' },
        padding: { top: '1rem', right: '1.25rem', bottom: '1rem', left: '1.25rem' },
      },
      typography: { fontStyle: 'italic' },
      css: `& cite{ display:block; margin-top:0.5rem; font-style:normal; font-weight:600; color:${muted}; }`,
    },

    // Image : coins arrondis + légende discrète
    'core/image': {
      css: `& img{ border-radius:0.5rem; height:auto; } & figcaption{ margin-top:0.5rem; font-size:0.875rem; color:${muted}; text-align:center; }`,
    },

    // Galerie : espace entre les vignettes
    'core/gallery': {
      spacing: { blockGap: { top: '1rem', left: '1rem' } },
      css: '& img{ border-radius:0.5rem; }',
    },

    // Média & texte : grille desktop (l'empilement mobile est dans app.css)
    'core/media-text': {
      spacing: { margin: { top: '1.5rem', bottom: '1.5rem' } },
      css: '& .wp-block-media-text__media img{ width:100%; height:auto; border-radius:0.5rem; }',
    },

    // Colonnes : écart entre colonnes (empilement mobile dans app.css)
    'core/columns': {
      spacing: { blockGap: { top: '2rem', left: '2rem' } },
    },

    // Boutons : écart entre boutons
    'core/buttons': {
      spacing: { blockGap: { top: '0.75rem', left: '0.75rem' } },
    },

    // Bouton secondaire (variation « outline »)
    'core/button': {
      variations: {
        outline: {
          color: { background: 'transparent', text: ink },
          border: { color: ink, width: '1px', style: 'solid' },
          css: `&:hover{ background:${ink}; color:#fff; } &:focus{ background:${ink}; color:#fff; }`,
        },
      },
    },

    // Groupe : rythme vertical interne + fond optionnel via la classe is-style
    'core/group': {
      spacing: { blockGap: '1rem' },
    },
  },
};
