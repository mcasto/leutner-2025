<?php

use Castoware\Database;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
$db = (new Database)->db;

$convertScript = __DIR__ . '/convert-chl.js';
$chlFile       = "/Users/mikecasto/Desktop/chl-article.html";
$outputFile    = "julia.md";
$imagePath     = dirname(__DIR__, 3) . '/article-images/' . pathinfo($outputFile, PATHINFO_FILENAME);

// categories: 1-culture, 2-economics, 3-race
$rec = [
 '_id'      => pathinfo($outputFile, PATHINFO_FILENAME),
 'folder'   => 'Cuenca High Life',
 'label'    => 'Julia: An expat committed to her community and sharing her love of the Ecuadorian people',
 'byline'   => 'Carol E. Leutner',
 'date'     => '2025-03-2',
 'url'      => 'https://cuencahighlife.com/julia-an-expat-committed-to-her-community-and-sharing-her-love-of-the-ecuadorian-people/',
 'category' => 5,
];

$exists = $db->fetch("SELECT * FROM articles WHERE _id=?", $rec['_id']);
if ($exists) {
 print_r(['error' => 'Article already exists in database']);
 die();
}

$sortOrder         = $db->fetch("SELECT MAX(sort_order) AS sortOrder FROM articles");
$rec['sort_order'] = ($sortOrder->sortOrder) + 10;

$db->query("INSERT INTO articles %v", $rec);

if (! file_exists($imagePath)) {
 mkdir($imagePath);
}

$cmd = [
 "node",
 "\"$convertScript\"",
 "\"$chlFile\"",
 "\"$outputFile\"",
 "\"$imagePath\"",
];

$imageList = shell_exec(implode(" ", $cmd));

print $imageList;
