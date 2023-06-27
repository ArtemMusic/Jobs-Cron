<?php
/**
 * Web Application stages
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace App;
use Core\Application;

class App extends \Core\Containers\AppContainer
{
	/**
	 * App loading stage handler
	 * @param Application $app
	 */
	protected function _boot(Application $app)
	{
		$app->registerModel('App\Models\User', 'user');
        $app->registerInstance('Classes\Client', 'client');
	}
	
	/**
	 * App shutdown stage handler
	 * Enable by config app.debug_shutdown
	 * @param Application $app
	 */
	protected function _shutdown(Application $app, \Components\Route $route, \Components\Request $request)
	{
		$execution_time = round((microtime(true) - $app->startTime(true)), 5);
		$memory_peak_usage = memory_get_peak_usage(true)/1024/1024;
		$mysqlQueries = $app->getMysqlQueries();
		$currentRoute = $route->getCurrent();
		$skip_uri = [
			'/test/best',
		];
		$l = null;
		if (!in_array($currentRoute->uri, $skip_uri)) {
			if ($currentRoute->method == 'CLI') {
				if ($execution_time >= 65) {
					$l = logger('system/warn.slow.65.log');
				}
			} else {
				if ($execution_time >= 60) {
					$l = logger('system/warn.slow.60.log');
				} else if ($execution_time >= 1) {
					$l = logger('system/warn.slow.01.log');
				}
			}
			if ($memory_peak_usage >= 32) {
				$l = logger('system/warn.memory.32.log');
			}
			if ($mysqlQueries->count >= 99) {
				$l = logger('mysql/warn.count.99.log');
			}
			if ($mysqlQueries->time >= 1) {
				$l = logger('mysql/warn.time.01.log');
			}
		}
		if ($l) {
			$lavg = sys_getloadavg();
			$lavg = array_map(function($val) {
				return round($val, 2);
			}, $lavg);
			$l->log(
				$currentRoute,
				'Load avg: '.join(' ', $lavg),
				'Response code: '.Response::getHttpCode(),
				'Execution time: '.$execution_time.' sec',
				'Memory peak usage: '.$memory_peak_usage.' mb',
				'MySQL queries: '.$mysqlQueries->count,
				'MySQL time: '.round($mysqlQueries->time, 5)
			);
		}
		if ($request->hasCli()) {
			exit;
		}
	}
}
