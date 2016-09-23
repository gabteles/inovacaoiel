<?php

use Phpmig\Adapter;
use \Pimple\Container;

$rawSettings = require('src/settings.php');
$settings = $rawSettings['settings'];

$container = new Container();

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

$container['db'] = function() use ($settings) {
  $url = parse_url($settings['orm']['url']);

  $server = $url["host"];
  $port = $url["port"];
  $username = $url["user"];
  $password = $url["pass"];
  $db = substr($url["path"], 1);

  ORM::configure($settings);
  ORM::configure("mysql:host=$server;port=$port;dbname=$db");
  ORM::configure('username', $username);
  ORM::configure('password', $password);

  return ORM::get_db();
};

$container['phpmig.adapter'] = function() use ($container) {
  return new Adapter\PDO\Sql($container['db'], 'migrations');
};

return $container;
