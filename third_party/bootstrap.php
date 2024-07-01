<?php
require_once __DIR__ . "/vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;

$ini_array = parse_ini_file ( __DIR__ .  '/../common/config.ini', true );
$dbname = $ini_array ['db_setting'] ['dbname'];
$host = $ini_array ['db_setting'] ['host'];
$user = $ini_array ['db_setting'] ['user'];
$pass = $ini_array ['db_setting'] ['pass'];

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $host,
    'database'  => $dbname,
    'username'  => $user,
    'password'  => $pass,
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

spl_autoload_register(function ($class) {
    if (strpos($class, 'App\\Models\\') === 0) {

        $class = str_replace('App\\Models\\', '', $class);
        $file = __DIR__ . '/app/Models/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

