<?php

namespace App;

class Parser
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function parse()
    {
        $file = fopen($this->url, 'r');
        while (!feof($file)) {
            if (strpos(fgets($file), '<item>') !== false) {
                $buf = [];
                foreach ($this->itemHandler($file) as $key => $value) {
                    $buf[$key] = $value;
                }
                yield new \ArrayObject($buf);
                unset($buf);
            }
        }
        fclose($file);
    }

    private function itemHandler($file)
    {
        while (true) {
            $str = fgets($file);
            if (strpos($str, '</item>') !== false) {
                break;
            }
            $this->tagParser($str, $tag, $params, $value);
            yield $tag => isset($params) ? ['value' => $value, 'params' => $params] : $value;
        }
    }

    private function tagParser($source, &$tag, &$params, &$value)
    {
        $tagBuf = substr($source, strpos($source, '<') + 1,
            (strpos($source, '>') - strpos($source, '<') - 1));
        $buf = explode(' ', $tagBuf);
        if (count($buf) === 1) {
            $tag = current($buf);
        } else {
            $tag = reset($buf);
            $params = $buf;
        }
        $strTagLen = strlen($tag);
        $startPos = strpos($source, $tag . '>') + $strTagLen + 1;
        $value = substr($source,
            $startPos, (strpos($source, '</' . $tag) - $startPos));
    }
}