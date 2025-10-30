<?php

use Castoware\Database;

require_once(dirname(__DIR__) . '/vendor/autoload.php');
$db = (new Database)->db;

$nav = $db->fetchAll("SELECT * FROM navigation");
$paths = array_map(function ($rec) {
  return $rec->path;
}, $nav);

print_r($paths);
