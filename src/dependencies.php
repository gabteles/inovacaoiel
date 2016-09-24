<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['db'] = function ($c) {
  $url = parse_url($c->get('settings')['orm']['url']);

  $server = $url["host"];
  $port = $url["port"];
  $username = $url["user"];
  $password = $url["pass"];
  $db = substr($url["path"], 1);

  ORM::configure("mysql:host=$server;port=$port;dbname=$db");
  ORM::configure('username', $username);
  ORM::configure('password', $password);

  return ORM::get_db();
};
