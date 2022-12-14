<?php

namespace App;

class ParserWithoutGenerators
{
    private $url, $buf, $result;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function parse()
    {
        $flag = null;
        $counter = 0;
        $file = fopen($this->url, 'r');
        while (!feof($file)) {
            $str = fgets($file);
            if (strpos($str, '<item>') !== false) {
                $flag = true;
                continue;
            }
            if (strpos($str, '</item>') !== false) {
                $flag = false;
                $this->result[] = new \ArrayObject($this->buf[$counter]);
                unset($this->buf[$counter]);
                $counter++;
            }
            if ($flag) {
                $this->tagParser($str, $tag, $params, $value);
                $this->buf[$counter][$tag] = isset($params) ? ['value' => $value, 'params' => $params] : $value;
            }
        }
        fclose($file);

        return $this->result;
    }

    private function tagParser($source, &$tag, &$params, &$value)
    {
        $tagBuf = substr(
            $source,
            strpos($source, '<') + 1,
            (strpos($source, '>') - strpos($source, '<') - 1)
        );
        $buf = explode(' ', $tagBuf);
        if (count($buf) === 1) {
            $tag = current($buf);
        } else {
            $tag = reset($buf);
            $params = $buf;
        }
        $strTagLen = strlen($tag);
        $startPos = strpos($source, $tag . '>') + $strTagLen + 1;
        $value = substr(
            $source,
            $startPos,
            (strpos($source, '</' . $tag) - $startPos)
        );
    }
}
