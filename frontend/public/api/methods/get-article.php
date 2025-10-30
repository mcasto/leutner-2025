<?php
function getArticle($db, $request, $util)
{
  $articlesPath = dirname(__DIR__) . '/articles';

  try {
    $article = $db->fetch("SELECT * FROM articles WHERE _id=?", $request->params->id);

    $articleFile = $articlesPath . "/" . $article->_id . ".md";
    if (!file_exists($articleFile)) {
      error_log(print_r(['Article Not Found' => $articleFile], true));
      $util->fail("Article Contents Not Found");
    }

    $article = [
      '_id' => $article->_id,
      'title' => $article->label,
      'byline' => $article->byline,
      'date' => date("M j, Y", strtotime($article->date)),
      'url' => $article->url,
      'urlLabel' => $article->folder != 'Website' ? 'View Original at ' . $article->folder : '',
      'contents' => file_get_contents($articleFile)
    ];

    $util->success($article);
  } catch (Exception $e) {
    error_log(print_r(['file' => __FILE__, 'error' => $e->getMessage()], true));
    $util->fail(false);
  }
}
