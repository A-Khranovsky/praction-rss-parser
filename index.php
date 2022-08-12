<?php

$url = 'https://www.liga.net/news/all/rss.xml';

function objecter()
{

}

function reader($file)
{
    while (true) {
        $str = fgets($file);
        if (mb_strstr($str, '</item>')) {
            fgets($file);
            break;
        }
          yield $str;
//        yield [
//            mb_substr($str, mb_strpos($str, '<') + 1, mb_strpos($str, '>') - 1) => $str
//        ];
    }
}

function parser($url)
{
    $str = [];
    $file = fopen($url, 'r');
    while (!feof($file)) {
        if (fgets($file) == ('<item>' . PHP_EOL)) {
            foreach (reader($file) as $item) {
                $str [] = $item;
            }
            yield $str;
            unset($str);
        }

    }

    fclose($file);

}

$data = parser($url);
foreach ($data as $item) {
    echo '<pre>';
    var_dump($item);
    echo '</pre>';
}
