import js from '@eslint/js';
import prettier from 'eslint-config-prettier';
import typescript from 'typescript-eslint';

export default [
    js.configs.recommended,
    ...typescript.configs.recommended,
    {
        ignores: ['vendor', 'node_modules', 'public', 'tailwind.config.js'],
    },
    prettier,
];
