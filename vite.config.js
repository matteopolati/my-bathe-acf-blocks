import { defineConfig } from 'vite';
import { existsSync, readdirSync } from 'node:fs';
import { resolve } from 'node:path';
import packageJson from './package.json';

const { entry, output } = packageJson.config.js;
const { src } = packageJson.config.css;

const outDir = output.split('/')[0];
const sassMainEntry = resolve(`${src}/main.scss`);
const sassBlocksDir = resolve(`${src}/blocks`);
const jsBlocksDir = resolve('src/js/blocks');

const blockEntries = existsSync(sassBlocksDir)
  ? readdirSync(sassBlocksDir, { withFileTypes: true })
      .filter((file) => file.isFile() && file.name.endsWith('.scss') && !file.name.startsWith('_'))
      .reduce((entries, file) => {
        const fileName = file.name.replace(/\.scss$/u, '');
        entries[`blocks/${fileName}`] = resolve(sassBlocksDir, file.name);
        return entries;
      }, {})
  : {};

const blockJsEntries = existsSync(jsBlocksDir)
  ? readdirSync(jsBlocksDir, { withFileTypes: true })
      .filter((file) => file.isFile() && file.name.endsWith('.js') && !file.name.startsWith('_'))
      .reduce((entries, file) => {
        const fileName = file.name.replace(/\.js$/u, '');
        entries[`js/blocks/${fileName}`] = resolve(jsBlocksDir, file.name);
        return entries;
      }, {})
  : {};

export default defineConfig(({ mode }) => {
  return {
    build: {
      outDir,
      emptyOutDir: false,
      rollupOptions: {
        input: {
          'js/main': resolve(entry),
          main: sassMainEntry,
          ...blockEntries,
          ...blockJsEntries,
        },
        output: {
          entryFileNames: '[name].js',
          chunkFileNames: 'js/[name].js',
          assetFileNames: ({ name }) => {
            if (name && name.endsWith('.css')) {
              const normalizedName = name.replace(/\\/gu, '/');

              if (normalizedName.includes('/blocks/')) {
                return 'css/blocks/[name][extname]';
              }

              return 'css/[name][extname]';
            }

            return 'assets/[name][extname]';
          },
        },
      },
      sourcemap: mode === 'development',
      minify: mode === 'production',
    },
  };
});
