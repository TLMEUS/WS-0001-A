<?php
/**
 * This file contains the App/Framework/Exceptions/PageNotFoundException.php class for project WS-0000-A.
 *
 * File information:
 * Project Name: WS-0000-A
 * Module Name: App/Framework/Exceptions
 * File Name: PageNotFoundException.php
 * File Author: Troy L Marker
 * Language: PHP 8.2
 *
 * File Copyright: 01/2024
 */
namespace Framework\Exceptions;

use DomainException;

/**
 * Class PageNotFoundException
 *
 * This class represents an exception that is thrown when a requested page is not found.
 * It extends the built-in DomainException class.
 */
class PageNotFoundException extends DomainException
{
}