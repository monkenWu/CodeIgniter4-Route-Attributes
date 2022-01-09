<?php

namespace monken\Ci4RouteAttributes\Exception;

use \Throwable;
use monken\Ci4RouteAttributes\Exception\RouteAttributesExceptionInterface;

class RouteAttributesException extends \Exception implements RouteAttributesExceptionInterface
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}