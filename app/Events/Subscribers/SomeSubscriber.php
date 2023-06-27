<?php
/**
 * CRM Widget events subscriber
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
namespace App\Events\Subscribers;
use Core\Models\Event;

abstract class SomeSubscriber
{
    /**
     * Widget installation handler
     * @param Event $event
     */
	public static function onSomeEvent(Event $event)
	{
		// code...
	}
	
   /**
     * Events subscriber
	 * @param Events $events
     */
	public static function subscribe(\Components\Events $events)
	{
		$events->on('some.event', static::class.'@onSomeEvent');
	}
}
