<?php
/**
 * This file contains the config/routes.php for project WS-0001-A.
 *
 * File information:
 * Project Name: WS-0001-A
 * Module Name: config
 * File Name: routes.php
 * File Author: Troy L. Marker
 * Language: PHP 8.3
 *
 * File Copyright: 01/2024
 */
$router = new Framework\Router;

$router->add(path: "/setpass/{id:\d+}/{username:[a-zA-Z]*}/{app:\d+}/{dep:\d+}/{rol:\d+}", params: [
                                                                        "controller" => "home", "action" => "setpass"]);
$router->add(path: "/{app:\d+}", params: ["controller" => "home", "action" => "index"]);
$router->add(path: "/", params: ["controller" => "home", "action" => "noaccess"]);
$router->add(path: "/{controller}/{action}");

return $router;