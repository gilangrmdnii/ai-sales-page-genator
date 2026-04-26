<?php

/**
 * Laravel - PHP built-in server router.
 *
 * If the request matches a real file in /public (CSS, JS, images, fonts),
 * return false so PHP serves it directly. Otherwise hand off to public/index.php
 * for Laravel routing.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
