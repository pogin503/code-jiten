<?php
define('DB_DRIVER', 'pgsql');
define('DB_HOST', 'localhost');
define('DB_DATABASE', 'postgres');
define('DB_PORT', '5432');
define('DB_CHARSET', 'UTF8');
define('DB_USERNAME', 'postgres');
define('DB_PASSWD', 'postgres');
define('PDO_DSN', DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_DATABASE . ";port=" . DB_PORT. "charset=" . DB_CHARSET);

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => DB_DRIVER,
    'host'      => DB_HOST,
    'database'  => DB_DATABASE,
    'port'      => DB_PORT,
    'username'  => DB_USERNAME,
    'password'  => DB_PASSWD,
    'charset'   => DB_CHARSET,
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container as Container;
$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();
?>
