<?php

use Castoware\Database;

require_once dirname(__DIR__) . '/vendor/autoload.php';
$db = (new Database)->db;

$items = json_decode(file_get_contents($argv[1]));

$idPrefix = 0;

function genID($prefix)
{
 $n     = uniqid('', true);
 $parts = explode(".", $n);
 return sprintf("%03s", base_convert($prefix, 10, 36)) . "-" . base_convert($parts[0], 16, 36) . "-" . base_convert($parts[1], 10, 36);
}

function flatten($items, &$r, $parent = null)
{
 global $idPrefix;

 foreach ($items as $item) {
  $idPrefix++;
  $item->id     = genID($idPrefix);
  $item->parent = $parent;
  $c            = isset($item->children) ? $item->children : null;
  unset($item->children);
  $r[] = $item;
  if ($c) {
   flatten($c, $r, $item->id);
  }
 }
}

$r = [];
flatten(json_decode(file_get_contents(dirname(__DIR__, 3) . "/src/assets/navigation.json")), $r);

foreach ($r as $rec) {
 $db->query("INSERT INTO navigation %v", (array) $rec);
}
