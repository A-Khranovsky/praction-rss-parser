<?php

function parser($url)
{
    $file = fopen($url, 'r');
    while (!feof($file)) {
        if (fgets($file) == ('<item>' . PHP_EOL)) {
            $buf = [];
            foreach (itemHandler($file) as $key => $value) {
                $buf[$key] = $value;
            }
            yield new \ArrayObject($buf);
            unset($buf);
        }
    }
    fclose($file);
}
