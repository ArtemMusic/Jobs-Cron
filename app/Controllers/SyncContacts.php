<?php

namespace App\Controllers;

use App\Jobs\TransferNumbersJob;

class SyncContacts extends \Core\Controllers\Controller
{
    public function index()
    {
        $l = logger('cron.log');
        $l->log('import contacts start');

        $job = new TransferNumbersJob();
        $queue = queue()->push($job, 'dev6');
        $queue->setState(0);

        ajaxSuccess(true);
    }
}