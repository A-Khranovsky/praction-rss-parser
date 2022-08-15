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
                foreach (itemHandler($file) as $key => $value) {
                    $buf[$key] = $value;
                }
                yield new \ArrayObject($buf);
                unset($buf);
            }
        }
        fclose($file);
    }

    private function itemHandler()
    {

    }

    private function tagParser()
    {

    }
}