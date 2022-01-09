<?php

namespace monken\Ci4RouteAttributes\Exception;

use monken\Ci4RouteAttributes\Exception\RouteAttributesException;

class RouteRESTfulException extends RouteAttributesException
{

	public function __construct(string $message)
	{
		parent::__construct($message);
	}

	public static function forAllowType(string $name, string $type): RouteAttributesException
	{
		return new self("The '{$type}' type defined in the {$name} RESTful route is not among the allowed types. Available types: resource, presenter.");
	}
}
