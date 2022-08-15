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
            if (fgets($file) == ('<item>' . PHP_EOL)) {
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
        $tag = null;
        $params = null;
        $value = null;

        while (true) {
            $str = fgets($file);
            if (mb_strstr($str, '</item>')) {
                fgets($file);
                break;
            }
            $this->tagParser($str, $tag, $params, $value);
            yield $tag => isset($params) ? ['value' => $value, 'params' => $params] : $value;
        }
    }

    private function tagParser($source, &$tag, &$params, &$value)
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
}