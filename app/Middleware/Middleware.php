<?php
/**
 * MVC Middleware
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace App\Middleware;

class Middleware
{
	protected $request;
	
    public function __construct(\Components\Request $request)
    {
		$this->request = $request;
    }
}
