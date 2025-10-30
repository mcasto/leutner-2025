<?php
function getArticles($db, $request, $util)
{

 try {
  $articleList = $db->fetchAll("SELECT a.*, c.label AS category_label, c.sort_order AS category_order FROM articles a LEFT JOIN article_categories c ON c.id=a.category ORDER BY `date` DESC");

  $articles = [];

  foreach ($articleList as $article) {
   $articles[] = [
    'id'             => $article->id,
    '_id'            => $article->_id,
    'title'          => $article->label,
    'byline'         => $article->byline,
    'date'           => date("M j, Y", strtotime($article->date)),
    'category_id'    => $article->category,
    'category_label' => $article->category_label,
    'category_order' => $article->category_order,
   ];
  }

  $util->success($articles);
 } catch (Exception $e) {
  error_log(print_r(['get-articles' => $e->getMessage()], true));
  $util->fail($e->getMessage());
 }
}
