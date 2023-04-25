<?php
class HttpServer
{
    private $host;
    private $port;
    private $baseUrl;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->baseUrl = "http://{$host}:{$port}/";
    }

    public function get($endpoint)
    {
        $url = $this->baseUrl . $endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }
}