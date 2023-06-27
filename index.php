<?php
/**
 * Web Application
 * @author Vlad Ionov <vlad@ufee.ru>
 */
define('ROOT', realpath(__DIR__));

$application = require ROOT . '/core/loader.php';
$application->run();
