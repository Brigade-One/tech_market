<?php
class LogHandler
{
    public function logEvent($event)
    {
        $logMessage = date('Y-m-d H:i:s') . ': ' . $event . "\n";
        $file = fopen("logs/log.txt", "a");
        fwrite($file, $logMessage);
        fclose($file);
    }
}