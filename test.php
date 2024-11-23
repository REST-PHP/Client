<?php

use Rest\Http\Method;
use Rest\Http\Parameters\ImmutableParameterCollection;
use Rest\Http\Request\RestRequest;
use Rest\Http\Request\RestRequestBuilder;

require_once "vendor/autoload.php";

$request = new RestRequest(
    'users', method: Method::PUT, headers: new ImmutableParameterCollection(['test' => ['testing']])
);

$builder = RestRequestBuilder::fromRequest($request)
    ->withSegment('page', '');

var_dump($builder);