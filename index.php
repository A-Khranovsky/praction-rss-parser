<?php

$url = 'https://www.liga.net/news/all/rss.xml';

function tagParser($source, &$tag, &$params, &$value)
{
    $tagBuf = mb_substr($source, mb_strpos($source, '<') + 1, mb_strpos($source, '>') - 1);
    $buf = explode(' ', $tagBuf);
    if (count($buf) === 1) {
        $tag = current($buf);
    } else {
        $tag = reset($buf);
        $params = $buf;
    }
    $strTagLen = strlen($tag);
    $value = mb_substr($source, mb_strpos($source, $tag . '>') + $strTagLen + 1,
        mb_strpos($source, '</' . $tag) - $strTagLen - 2);

}

function reader($file)
{
    $tag = null;
    $params = null;
    $value = null;

    while (true) {
        $str = fgets($file);
        if (mb_strstr($str, '</item>')) {
            fgets($file);
            break;
        }

        tagParser($str, $tag, $params, $value);
        yield ['key' => $tag,
            'value' => $value,
            'params' => $params
        ];
    }
}

function parser($url)
{
    $str = [];
    $file = fopen($url, 'r');
    while (!feof($file)) {
        if (fgets($file) == ('<item>' . PHP_EOL)) {
            foreach (reader($file) as $item) {
                $str[$item['key']] = isset($item['params']) ? [$item['value'], $item['params']] : $item['value'];
            }
            yield $str;
            unset($str);
        }
    }
    fclose($file);

}

$source = [];

$data = parser($url);
foreach ($data as $item) {
    $obj = new class {
    };
    foreach ($item as $key => $value) {
        $obj->$key = $value;
    }
    $source[] = $obj;
    unset($obj);
}

echo '<pre>';
var_dump($source);
echo '</pre>';
