<?php
spl_autoload_register();
use App\Parser;
$url = 'https://www.liga.net/tech/all/rss.xml';


$source = [];

$data = new Parser($url);
foreach ($data->parse() as $item) {
    $source[] = $item;
}

echo '<pre>';
var_dump($source);
echo '</pre>';

echo memory_get_usage();