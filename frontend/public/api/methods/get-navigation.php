<?php

function getNavigation($db, $request, $util)
{
  $nav = $db->fetchAll("SELECT * FROM navigation WHERE visible=1 ORDER BY sort_order");
  $nav = array_map(
    function ($rec) {
      $rec->parent = $rec->parent ?? '';
      return $rec;
    },
    $nav
  );

  $tree = $util->buildNavTree($nav);

  $util->success($tree);
}
