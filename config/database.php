<?php
define('DB_DRIVER', 'pgsql');
define('DB_DATABASE', 'postgres');
define('DB_USERNAME', 'postgres');
define('DB_PASSWD', 'postgres');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'UTF8');
define('DB_PORT', '5432');
define('PDO_DSN', DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_DATABASE . ";port=" . DB_PORT. "charset=" . DB_CHARSET);
?>
