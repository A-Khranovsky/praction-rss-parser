<?php


function itemHandler($file)
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
        yield $tag => isset($params) ? ['value' => $value, 'params' => $params] : $value;
    }
}