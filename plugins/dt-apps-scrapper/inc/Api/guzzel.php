<?php 

require 'vendor/autoload.php';
use GuzzleHttp\Client;

$client = new GuzzleHttp\Client();

$client = new Client();   
$response = $client->request('GET', "https://jsonplaceholder.typicode.com/todos/1");
return $response->getBody();

  