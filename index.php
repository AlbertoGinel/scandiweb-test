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

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UFT-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);


if($parts[3] != "products"){
  http_response_code(404);
  exit;
}

$id = $parts[4] ?? null;

//TODO: move them from here

$database = new Database("localhost", "my_db", "root", "");
//host, dbname, user, password
//$database = new Database("sql208.infinityfree.com", "if0_36889266_products", "if0_36889266", "dPqyG6EA4gHcrA ");

$gateway = new ProductGateway($database);

$controller = new ProductController($gateway);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    echo 'options';
}

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);