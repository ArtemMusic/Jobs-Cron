<?php

namespace App\Controllers;

use App\Jobs\TransferNumbersJob;

class TransferNumbers extends \Core\Controllers\Controller
{
    public function index()
    {
        $job = new TransferNumbersJob();
        $queue = queue()->push($job, 'dev6');
        $queue->setState(0);

        ajaxSuccess(true);
    }
}