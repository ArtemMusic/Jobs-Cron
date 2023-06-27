<?php
/**
 * Some job
 */
namespace App\Jobs;

class SomeJob extends \Core\Jobs\QueueJob
{
	/**
	 * Handle job
	 */
    public function handle()
    {
		sleep(10);
		echo 'My qjob: '.$this->qjob_id;
	}
}
