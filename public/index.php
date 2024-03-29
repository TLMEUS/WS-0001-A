<?php
/**
 * This file contains the public/index.php for project WS-0001-A.
 *
 * File information:
 * Project Name: WS-0001-A
 * Module Name: public
 * File Name: index.php
 * File Author: Troy L. Marker
 * Language: PHP 8.3
 *
 * File Copyright: 01/2024
 */
declare(strict_types=1);

define("ROOT_PATH", dirname(path: __DIR__));
spl_autoload_register(callback: function (string $class_name) {
    require ROOT_PATH . "/src/" . str_replace(search: "\\", replace: "/", subject: $class_name) . ".php";
});
$dotenv = new Framework\Dotenv;
$dotenv->load(path: dirname(path: ROOT_PATH) ."\.env");
set_error_handler(callback: "Framework\ErrorHandler::handleError");
set_exception_handler(callback: "Framework\ErrorHandler::handleException");
$router = require ROOT_PATH . "/config/routes.php";
$container = require ROOT_PATH . "/config/services.php";
$middleware = require ROOT_PATH . "/config/middleware.php";
$dispatcher = new Framework\Dispatcher(router: $router, container: $container, middleware_classes: $middleware);
$request = Framework\Request::createFromGlobals();
try {
    $response = $dispatcher->handle($request);
} catch (ReflectionException $e) {
}
/** @noinspection PhpUndefinedVariableInspection */
$response->send();
