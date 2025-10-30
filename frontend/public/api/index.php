<?php

use Castoware\Database;
use Castoware\Request;
use Castoware\Util;

header("Access-Control-Allow-Origin: *");

date_default_timezone_set('America/Guayaquil');

ini_set("error_log", __DIR__ . '/error.log');

require_once "vendor/autoload.php";
$router  = new AltoRouter();
$util    = new Util;
$request = new Request;
$db      = (new Database)->db;

require_once __DIR__ . '/methods/index.php';

// add get methods
$router->addRoutes([
 ['get', '/api/get-article/[:id]', 'getArticle'],
 ['get', '/api/get-articles', 'getArticles'],
 ['get', '/api/get-lectures', 'getLectures'],
 ['get', '/api/get-navigation', 'getNavigation'],
 ['get', '/api/get-reviews/[:slug]', 'getReviews'],
 ['get', '/api/get-press-releases', 'getPressReleases'],
]);

// add post methods
$router->addRoutes([
 ['post', '/api/send-contact', 'sendContact'],
]);

$match = $router->match();

if (is_array($match) && is_callable($match['target'])) {
 if ($request->auth) {
  if (! $util->getUser('token', $request->auth)) {
   $util->fail("Invalid Authorization");
  }

 }

 $request->params = (object) $match['params'];
 call_user_func_array(
  $match['target'],
  [
   'db'      => $db,
   'request' => $request,
   'util'    => $util,
  ]
 );
} else {
 // no route was matched
 header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}
