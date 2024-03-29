<?php
/**
 * This file contains the App/Framework/ControllerRequestHandler.php class for project WS-0000-A.
 *
 * File information:
 * Project Name: WS-0000-A
 * Module Name: App/Framework
 * File Name: ControllerRequestHandler.php
 * File Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Copyright: 01/2024
 */
declare(strict_types=1);

namespace Framework;

/**
 * Represents a request handler that delegates the handling of a request to a controller.
 *
 * @implements RequestHandlerInterface
 */
class ControllerRequestHandler implements RequestHandlerInterface {

    /**
     * Constructor for the class.
     *
     * @param Controller $controller The controller object.
     * @param string $action The action to be performed.
     * @param array $args The arguments for the action.
     *
     * @return void
     */
    public function __construct(private readonly Controller $controller,
                                private readonly string     $action,
                                private array               $args) {
    }

    /**
     * Handles the request and returns a response.
     *
     * @param Request $request The request object.
     *
     * @return Response The response object.
     */
    public function handle(Request $request): Response {
        $this->controller->setRequest($request);
        return ($this->controller)->{$this->action}(...$this->args);
    }
}