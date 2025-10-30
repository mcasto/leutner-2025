<?php
function getPressReleases($db, $request, $util)
{
 try {
  $recs     = $db->fetchAll("SELECT * FROM press_releases ORDER BY release_date DESC");
  $releases = array_map(function ($rec) {
   $rec->file     = dirname(__DIR__, 2) . $rec->contents;
   $rec->contents = file_get_contents($rec->file);
   return $rec;
  }, $recs);

  $util->success($releases);
 } catch (Exception $e) {
  error_log(print_r(['get-press-releases-error' => $e->getMessage()], true));
  $util->fail('Error retrieving press releases');
 }
}
