<?php

// Silence PHP 8.5 deprecation warnings from vendor files
error_reporting(E_ALL & ~E_DEPRECATED);

// Forward Vercel requests to the normal Laravel index file
try {
    require __DIR__ . '/../public/index.php';
} catch (Throwable $e) {
    error_log('VERCEL_BOOT_ERROR: ' . $e::class . ': ' . $e->getMessage());
    error_log('VERCEL_BOOT_ERROR_FILE: ' . $e->getFile() . ':' . $e->getLine());
    error_log($e->getTraceAsString());

    http_response_code(500);

    if ((bool) (getenv('APP_DEBUG') ?: false)) {
        header('Content-Type: text/plain; charset=UTF-8');
        echo $e::class . ': ' . $e->getMessage() . PHP_EOL;
        echo $e->getFile() . ':' . $e->getLine() . PHP_EOL;
    } else {
        echo 'Internal Server Error';
    }
}
