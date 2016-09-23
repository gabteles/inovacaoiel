<?php
/*
require_once(__DIR__ . '/models/migration.php');

$c = $app->getContainer();
$url = parse_url($c->get('settings')['orm']['url']);

$server = $url["host"];
$port = $url["port"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

ORM::configure($settings);
ORM::configure("mysql:host=$server;port=$port;dbname=$db");
ORM::configure('username', $username);
ORM::configure('password', $password);

try {
  $migrations = Model::factory('Migration')->find_many();
} catch (Exception $e) {
  $migrations = [];
}

$dir = __DIR__ . "/../migrations";
$dh  = opendir($dir);
$filenames = [];

while (false !== ($filename = readdir($dh))) {
  if (in_array($filename, ['.', '..'])) {
    continue;
  }

  $pattern = '/^(\d)+\-[a-z\-]+\.sql$/';
  preg_match($pattern, $filename, $matches);

  if (empty($matches)) {
    continue;
  }

  $filenames[] = $filename;
}

sort($filenames);
foreach ($filenames as $filename) {
  $filepath = __DIR__ . "/../migrations/" . $filename;

  $file = fopen($filepath, 'r') or die("Unable to open file!");
  $fileContent = fread($file, filesize($filepath));
  fclose($file);

  print_r($fileContent);

  ORM::raw_execute($fileContent);

}
*/
