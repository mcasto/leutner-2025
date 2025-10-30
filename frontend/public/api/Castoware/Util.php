<?php

namespace Castoware;

class Util
{
  public $externalPath, $isDevelopment, $user, $configFile;

  function __construct()
  {
    $this->isDevelopment  = !isset($_SERVER['DOCUMENT_ROOT']) || trim($_SERVER['DOCUMENT_ROOT']) == '' || !!stristr($_SERVER['DOCUMENT_ROOT'], '/programming-projects/');

    // set password from a file at castoware data repo
    $devPath= '/users/mikecasto/website-data-repo/leutner';
    $prodPath = "/home/u466389499/domains/castoware.com/data-repo/leutner";

    $this->externalPath = $this->isDevelopment ? $devPath : $prodPath;

    $this->configFile = $this->externalPath . '/db-config.json';
  }

  function compress($str)
  {
    return base64_encode(gzdeflate($str, 9));
  }

  function decompress($str)
  {
    return gzinflate(base64_decode($str));
  }

  function success($data = [], $compress = false)
  {
    $data = $compress ? $this->compress(json_encode($data)) : json_encode($data);

    exit(json_encode([
      'status' => 'ok',
      'data' => $data,
      'compressed' => $compress
    ]));
  }

  function fail($message)
  {
    die(json_encode(['status' => 'error', 'message' => $message]));
  }

  function getUser($field, $string)
  {
    $db = (new Database)->db;
    return $db->fetch("SELECT * FROM users WHERE %n=?", $field, $string);
  }

  function getImageAspectRatio($imagePath)
  {
    $imagePath = $_SERVER['DOCUMENT_ROOT'] . $imagePath;

    // Get the dimensions of the image
    $imageInfo = getimagesize($imagePath);

    if ($imageInfo === false) {
      // Failed to get image information
      return false;
    }

    // Extract width and height from image information
    $width = $imageInfo[0];
    $height = $imageInfo[1];

    // Calculate aspect ratio
    $aspectRatio = $width / $height;

    // Convert aspect ratio to standard format
    $standardRatio = $this->convertToStandardAspectRatio($aspectRatio);

    return $standardRatio;
  }

  function convertToStandardAspectRatio($aspectRatio)
  {
    // Define common aspect ratios
    $ratios = array(
      "16/9" => 16 / 9,
      "4/3" => 4 / 3,
      "3/2" => 3 / 2,
      "1/1" => 1
    );

    // Find the closest standard aspect ratio
    $closestRatio = "";
    $minDifference = PHP_INT_MAX;
    foreach ($ratios as $ratio => $standard) {
      $difference = abs($aspectRatio - $standard);
      if ($difference < $minDifference) {
        $minDifference = $difference;
        $closestRatio = $ratio;
      }
    }

    return $closestRatio;
  }

  function buildNavTree(array $elements, $parentId = '')
  {
    $branch = [];

    foreach ($elements as $element) {
      if ($element['parent'] === $parentId) {
        $children = $this->buildNavTree($elements, $element['_id']);
        if ($children) {
          $element['children'] = $children;
        }
        $branch[] = $element;
      }
    }

    return $branch;
  }
}
