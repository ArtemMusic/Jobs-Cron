<?php
/**
 * Web Application Controller pages
 */
namespace App\Controllers;
use Core\Application,
	App\Models\Company,
	App\Models\User,
	App\Models\Role,
	Components\Request,
	Components\Response,
	Components\Security\Crypto,
	Components\Sessions\Session,
	Components\Helpers\Hash,
	Components\Queue;

class Page extends \Core\Controllers\Controller
{
    public function __construct()
    {
        parent::__construct();
		
		$this->middleware('AllowOrigin');
	}
	
	/**
	 * Test 
	 */
    public function test(Request $request, Application $app, Queue $queue)
    {
		
	}
}
