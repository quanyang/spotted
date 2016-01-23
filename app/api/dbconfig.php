<?php

use Illuminate\Database\Capsule\Manager as Capsule;
/**
 * Configure the database and boot Eloquent
 */
$capsule = new Capsule;
$capsule->addConnection(array(
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'database'  => 'spotted',
    'username'  => 'root',
    'password'  => getenv('MYSQL_PASSWORD'),
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_bin',
    'prefix'    => ''
));
$capsule->setAsGlobal();
$capsule->bootEloquent();
// set timezone for timestamps etc
date_default_timezone_set('Asia/Singapore');
