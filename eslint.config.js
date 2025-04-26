import js from '@eslint/js';
import prettier from 'eslint-config-prettier';

export default [
    js.configs.recommended,
    {
        ignores: ['vendor', 'node_modules', 'public', 'tailwind.config.js'],
    },
    prettier,
];
