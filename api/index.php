<?php

// Silence PHP 8.5 deprecation warnings from vendor files
error_reporting(E_ALL & ~E_DEPRECATED);

// Forward Vercel requests to the normal Laravel index file
// Deployment update trigger
require __DIR__ . '/../public/index.php';
