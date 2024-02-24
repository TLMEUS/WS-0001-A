<?php
/**
 * This file contains the App/Framework/TemplateViewerInterface.php interface for project WS-0000-A.
 *
 * File information:
 * Project Name: WS-0000-A
 * Module Name: App/Framework
 * File Name: TemplateViewerInterface.php
 * File Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Copyright: 01/2024
 */
namespace Framework;

/**
 * Interface TemplateViewerInterface
 *
 * This interface defines the methods for a template viewer class.
 *
 * @package Application\Templates
 */
interface TemplateViewerInterface {
    public function render(string $template, array $data = []): string;    
}