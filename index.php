<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . "/src/$class.php",
        __DIR__ . "/src/Model/$class.php",
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            break;
        }
    }
});

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    http_response_code(200);
    //exit();
}


set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UFT-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);


if($parts[1] != "products"){
    http_response_code(404);
    exit;
}



$id = $parts[2] ?? null;

//TODO: move them from here

$database = new Database("r42ii9gualwp7i1y.chr7pe7iynqr.eu-west-1.rds.amazonaws.com", "q47eh5kszwn4rong", "kaxoov90gq6jix2g", "hnm4h32lfz9s5n04");
//host, dbname, user, password
//$database = new Database("", "", "", "");

$gateway = new ProductGateway($database);

$controller = new ProductController($gateway);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);