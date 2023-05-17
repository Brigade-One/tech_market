<?php
namespace TechMarket\Lib;

use Exception;

class LineDataReader
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function read()
    {
        $data = [];
        $this->filename = "c:\\OSPanel\\domains\\tech_market\\server\\data\\" . $this->filename;
        $handle = fopen($this->filename, "r");
        if (!$handle) {
            throw new Exception('Unable to open file with path ' . $this->filename . '.');
        }
        while (($line = fgets($handle)) !== false) {
            $line = json_decode($line, true);
            $data[] = $line;
        }
        fclose($handle);
        return $data;
    }

}