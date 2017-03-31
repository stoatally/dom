<?php

use Stoatally\Dom\DocumentFactory;
use Stoatally\Dom\XPathFactory;

require __DIR__ . '/../../vendor/autoload.php';

$factory = new DocumentFactory(new XPathFactory('atom'));
$tpl = $factory->createFromUri(__DIR__ . '/assets/atom-feed.html');
$data = json_decode(file_get_contents(__DIR__ . '/assets/data.json'), true);
$feed = $tpl->select('atom:feed');

// Set the site heading:
foreach ($feed->select('atom:title | atom:author/atom:name') as $node) {
    $node->setContents($data['site-title']);
}

// Set current date:
$feed->select('atom:updated')->setContents(
    (new DateTime())->format(DateTime::W3C)
);

// Create an entry element for each item:
$feed->select('atom:entry')->repeatNode($data['articles'], function($article, $data) {
    $article->select('atom:title')->setContents($data['title']);
    $article->select('atom:id')->setContents(
        trim(preg_replace('%\W+%', '-', strtolower($data['title'])), '-')
    );
    $article->select('atom:updated')->setContents(
        (new DateTime($data['date']))->format(DateTime::W3C)
    );
});

file_put_contents(__DIR__ . '/public/atom.xml', $tpl->saveXml());
