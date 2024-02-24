<?php
/**
 * This file contains the App/Framework/MiddlewareInterface.php interface for project WS-0000-A.
 *
 * File information:
 * Project Name: WS-0000-A
 * Module Name: App/Framework
 * File Name: MiddlewareInterface.php
 * File Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Copyright: 01/2024
 */
namespace Framework;

/**
 * Represents a middleware interface.
 */
interface MiddlewareInterface {
    /**
     * Processes the given request and passes it to the next request handler.
     *
     * @param Request $request The request to be processed.
     * @param RequestHandlerInterface $next The next request handler in the pipeline.
     *
     * @return Response The response returned by the next request handler.
     */
    public function process(Request $request, RequestHandlerInterface $next): Response;
}