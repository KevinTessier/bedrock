import path from 'node:path';
import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import { wordpressPlugin, wordpressThemeJson } from '@roots/vite-plugin';

export default defineConfig({
  // .env / .env.local / .env.[mode] lus depuis la racine Bedrock (pas le thème).
  // Seules les variables VITE_* sont exposées au client (import.meta.env).
  envDir: path.resolve(import.meta.dirname, '../../../..'),
  base: '/app/themes/sage/public/build/',
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/editor.css',
        'resources/js/editor.js',
      ],
      refresh: true,
      assets: ['resources/images/**', 'resources/fonts/**'],
    }),

    wordpressPlugin(),

    // Generate the theme.json file in the public/build/assets directory
    // based on the Tailwind config and the theme.json file from base theme folder
    wordpressThemeJson({
      disableTailwindColors: true,
      disableTailwindFonts: true,
      disableTailwindFontSizes: true,
      disableTailwindBorderRadius: true,
    }),
  ],
  resolve: {
    alias: {
      '@scripts': '/resources/js',
      '@styles': '/resources/css',
      '@fonts': '/resources/fonts',
      '@images': '/resources/images',
      '@blocks': '/resources/blocks',
    },
  },
  // Transforme le JSX des blocs Gutenberg via @wordpress/element.
  // `jsx: 'transform'` force le runtime CLASSIQUE (sinon Vite 8/rolldown part
  // en runtime automatique et cherche react/jsx-runtime).
  // Chaque bloc importe lui-même createElement / Fragment.
  esbuild: {
    jsx: 'transform',
    jsxFactory: 'createElement',
    jsxFragment: 'Fragment',
  },
});
