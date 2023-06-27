<?php
/**
 * MVC Middleware Auth Redirect
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace App\Middleware;
use Core\Http\Route;

class AuthRedirect extends Middleware
{
	/**
	 * Check user auth and redirect
	 */
	public function handle()
	{
		if (!app('auth')->check()) {
			to('LoginPage');
		}
	}
}