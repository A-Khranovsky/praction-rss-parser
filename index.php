<?php

require_once(__DIR__ . '/parser.php');
require_once(__DIR__ . '/tagParser.php');
require_once(__DIR__ . '/itemHandler.php');

$url = 'https://www.liga.net/news/all/rss.xml';


$source = [];

$data = parser($url);
foreach ($data as $item) {
    $source[] = $item;
}

echo '<pre>';
var_dump($source);
echo '</pre>';

echo $source[0]['title'];
