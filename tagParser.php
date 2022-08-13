<?php

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
