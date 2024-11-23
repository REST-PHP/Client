<?php

require 'vendor/autoload.php';

use Rest\RestClient;

$client = new RestClient();

$response = $client->executeGet('https://google.com');