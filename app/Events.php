<?php
/**
 * Web Application Events boot
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace App;

class Events extends \Core\Containers\EventsContainer
{
	/**
	 * App events init handler
	 * Enable cache by config app.cache_events
	 * Cli command: php cmd.php cache_events
	 * Will make cache in /app/Events/cache.php
	 * @param Events $events
	 */
	protected function _boot(\Components\Events $events)
	{
		$events->registerSubscribers([
			'\App\Events\Subscribers\SomeSubscriber',
		]);
	}
}
