<?php
declare(strict_types=1);

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'covoit_mvc_test');
define('DB_CHARSET', 'utf8mb4');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_TIMEZONE', '+00:00');

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/AgencyModel.php';