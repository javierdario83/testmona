<?php

declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_NAME = 'tarot_app';
const DB_USER = 'root';
const DB_PASS = '';

// Cambia esta ruta base según la carpeta en htdocs (ejemplo: /testmona)
const APP_BASE_PATH = '/testmona';

if (!function_exists('app_url')) {
    function app_url(string $path = ''): string
    {
        $base = rtrim(APP_BASE_PATH, '/');
        $normalized = ltrim($path, '/');

        if ($normalized === '') {
            return $base !== '' ? $base : '/';
        }

        return ($base !== '' ? $base : '') . '/' . $normalized;
    }
}

const APP_NAME = 'Brujosa Tarot';
const ADMIN_USER_DEFAULT = 'heidy';
const ADMIN_PASS_DEFAULT = 'brujosa123';
