<?php
require_once __DIR__ . "/vendor/autoload.php";

use Illuminate\Foundation\Application;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Translation\Translator;
use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Bus\BusServiceProvider;
use Maatwebsite\Excel\ExcelServiceProvider;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use Illuminate\Validation\ValidationServiceProvider;

$app = new Application();

$app->singleton('config', function () {
    $config = new \Illuminate\Config\Repository();

    $config->set('filesystems.disks', [
        'local' => [
            'driver' => 'local',
            'root' => __DIR__ . '/storage',
        ],
        'export_error' => [
            'driver' => 'local',
            'root' => __DIR__ . '/../upload_files/export_error_data',
        ],
    ]);
    $config->set('excel.exports.temp_path', __DIR__ . '/storage/framework/cache/laravel-excel');


    return $config;
});

$app->singleton('filesystem', function ($app) {
    return new FilesystemManager($app);
});

$app->singleton('translator', function ($app) {
    $loader = new FileLoader(new Filesystem(), __DIR__ . '/resources/lang');
    return new Translator($loader, 'en');
});

$app->singleton('validator', function ($app) {
    return $app->make('Illuminate\Validation\Factory');
});

$app->instance('path.storage', __DIR__ . '/storage');

$app->register(ExcelServiceProvider::class);
$app->register(BusServiceProvider::class);
$app->register(ValidationServiceProvider::class);

$ini_array = parse_ini_file(__DIR__ . '/../common/config.ini', true);
$dbname = $ini_array['db_setting']['dbname'];
$host = $ini_array['db_setting']['host'];
$user = $ini_array['db_setting']['user'];
$pass = $ini_array['db_setting']['pass'];

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

$app->instance('db', $capsule->getDatabaseManager());

$filesystem = $app->make('filesystem');

ExcelFacade::setFacadeApplication($app);

spl_autoload_register(function ($class) {
    if (strpos($class, 'App\\Models\\') === 0) {
        $class = str_replace('App\\Models\\', '', $class);
        $file = __DIR__ . '/app/Models/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});
