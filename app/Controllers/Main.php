<?php
/**
 * Main controller
 */
namespace App\Controllers;
use Components\Response;

class Main extends \Core\Controllers\Controller
{
	public function index()
	{
		return view('welcome', ['title' => 'Welcome']);
	}
}
