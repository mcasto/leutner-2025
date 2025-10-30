<?php
function getLectures($db, $request, $util)
{
  $photosPath = dirname(__DIR__, 2) . '/photos/';

  try {
    $lectures = $db->fetchAll("SELECT * FROM lectures ORDER BY `date` DESC");
    $lectures = array_map(function ($lecture) use ($photosPath) {
      $path = $photosPath . '/' . $lecture->_id;
      $lecture->meta = json_decode(file_get_contents($path . '/.meta.json'));
      $photoList = scandir($path);
      $photoList = array_filter($photoList, function ($filename) {
        return substr($filename, 0, 1) != '.';
      });
      $idx = 1;
      $photoList = array_map(function ($filename) use (&$idx, $lecture) {
        return [
          'value' => uniqid(),
          'label' => $idx++,
          'src' => "/photos/" . $lecture->_id . "/$filename"
        ];
      }, $photoList);
      $lecture->photos = array_values($photoList);
      $lecture->date = date("M j, Y", strtotime($lecture->date));

      return $lecture;
    }, $lectures);

    $util->success($lectures);
  } catch (Exception $e) {
    $util->fail($e->getMessage());
  }
}
