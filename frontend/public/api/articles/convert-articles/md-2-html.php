<?php

use FastVolt\Helper\Markdown;

require_once dirname(__FILE__, 3) . '/vendor/autoload.php';

$md      = Markdown::new ();
$content = $md->setContent(file_get_contents("/Users/mikecasto/programming-projects/leutner-quasar/public/api/articles/jeremiah-chl-paradigm.md"));

$output = $md->toHtml($content);

file_put_contents("/Users/mikecasto/programming-projects/leutner-quasar/public/press-releases/2025-03-21.html", $output);
