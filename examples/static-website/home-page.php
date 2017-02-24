<?php

use Stoatally\Dom\DocumentFactory;

require __DIR__ . '/../../vendor/autoload.php';

$tpl = (new DocumentFactory())->createFromUri(__DIR__ . '/assets/home-page.html');
$data = json_decode(file_get_contents(__DIR__ . '/assets/data.json'), true);

// Set the site heading:
foreach ($tpl->select('title | //header/h1') as $node) {
    $node->setContent($data['site-title']);
}

// Create an article element for each item:
$tpl->select('//article')->repeat($data['articles'], function($article, $data) {
    $article->select('h1')->setContent($data['title']);
    $article->select('.//date')->replaceWith($data['date']);
});

// Set the copyright year:
$tpl->select('//copyright-year')->replaceWith($data['copyright-year']);

file_put_contents(__DIR__ . '/public/index.html', "<!DOCTYPE html>\n" . $tpl->saveHtml());