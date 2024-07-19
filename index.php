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

echo json_encode(["we are "=>"in php"]);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

    echo json_encode(["it is"=>"options"]);

    header('Access-Control-Allow-Origin: *'); // Replace '*' with specific origin if needed
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // List allowed methods
    header('Access-Control-Allow-Headers: Content-Type, Authorization'); // List allowed headers
    header('Access-Control-Max-Age: 86400'); // Cache preflight response for 1 day
    http_response_code(418); // Respond with 200 OK
    exit();
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