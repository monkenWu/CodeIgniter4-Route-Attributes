<?php

namespace monken\Ci4RouteAttributes\Exception;

use monken\Ci4RouteAttributes\Exception\RouteAttributesException;

class RouteException extends RouteAttributesException
{

	public function __construct(string $message)
	{
		parent::__construct($message);
	}

	public static function forAllowMethod(string $path, string $method): RouteAttributesException
	{
		return new self("The '{$method}' method defined in the {$path} route is not among the allowed methods. Available methods: add, get, post, put, head, options, delete, patch, cli.");
	}
}
