<?php

include "Gateway.php";
include "SQLGateway.php";
include "Controller.php";

// for testing, use this url localhost/index.php/data
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// all of our endpoints start with /data
// everything else results in a 404 Not Found
if ($uri[2] !== 'data') {
    echo "Hello World";// header("HTTP/1.1 404 Not Found");
    exit();
}

// the user id is, of course, optional and must be a string:
$name = null;
if (isset($uri[3])) {
    $name = $uri[3];
}

// get the http method
$requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method and user ID to the PersonController and process the HTTP request:
$control = new Controller($requestMethod, $name, true);
$control->processRequest();
