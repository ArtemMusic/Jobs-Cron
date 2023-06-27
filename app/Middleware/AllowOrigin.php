<?php
/**
 * MVC Middleware AllowOrigin header
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace App\Middleware;
use Components\Response;

class AllowOrigin extends Middleware
{
	public function handle($allow = '*')
	{
		Response::allowOrigin($allow);
	}
}
