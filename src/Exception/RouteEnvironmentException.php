<?php

namespace monken\Ci4RouteAttributes\Exception;

use monken\Ci4RouteAttributes\Exception\RouteAttributesException;

class RouteEnvironmentException extends RouteAttributesException
{

	public function __construct(string $message)
	{
		parent::__construct($message);
	}

	public static function forAllowType(string $type): RouteAttributesException
	{
		return new self("The '{$type}' environment type is not among the allowed type. Available types: production, development, testing.");
	}
}
