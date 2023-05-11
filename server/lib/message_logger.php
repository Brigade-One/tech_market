<?php
class MessageLogger
{
    private $file;

    public function __construct($filename)
    {
        $this->file = fopen($filename, 'a');
    }

    public function log($message)
    {
        fwrite($this->file, $message . PHP_EOL);
    }

    public function close()
    {
        fclose($this->file);
    }
}