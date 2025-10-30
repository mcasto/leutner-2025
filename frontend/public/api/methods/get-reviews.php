<?php
function getReviews($db, $request, $util)
{
  $slug       = $request->params->slug;
  $reviewList = glob(dirname(__DIR__) . "/reviews/{$slug}/*.html");

  $reviews = array_map(function ($file) {
    return file_get_contents($file);
  }, $reviewList);

  $util->success($reviews);
}
