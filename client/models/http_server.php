<?php
class HttpServer
{
    private $url;

    function __construct($url)
    {
        $this->url = $url;
    }

    function getRequest()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}