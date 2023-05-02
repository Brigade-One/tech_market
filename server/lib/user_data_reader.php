<?php
class UserDataReader
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function read()
    {
        $users = [];
        $handle = fopen($this->filename, "r");
        if (!$handle) {
            throw new Exception('Unable to open file');
        }
        while (($line = fgets($handle)) !== false) {
            $user = json_decode($line, true);
            $users[] = $user;
        }
        fclose($handle);
        return $users;
    }

}