<?php
$rules = [];
$rules['GET'] = [
'/' => 'App/Controllers/Main@index',
'login' => 'App\Controllers\Auth\Authenticate@loginPage',
'logout' => 'App\Controllers\Auth\Authenticate@logout',
'user/{user_id}/{user}' => 'App\Controllers\Page@user',
'index' => 'App\Controllers\Page@index',
'page/example' => 'App\Controllers\Page@example',
'page/test' => 'App\Controllers\Page@test',
'tests' => 'App\Controllers\Tests@index'
];
$rules['HEAD'] = [

];
$rules['POST'] = [
'login' => 'App\Controllers\Auth\Authenticate@attempt'
];
$rules['PUT'] = [

];
$rules['DELETE'] = [

];
$rules['OPTIONS'] = [

];
$rules['CLI'] = [
'queue/process' => 'Core\Controllers\Queue@process',
'queue/jobs/{job_id=int}/work' => 'Core\Controllers\Queue@work'
];
$patterns = [];
$patterns['GET'] = [
'/' => '^/$',
'login' => '^login$',
'logout' => '^logout$',
'user/{user_id}/{user}' => '^user/([a-zA-Z0-9\.\-]+)/([a-zA-Z0-9\.\-]+)$',
'index' => '^index$',
'page/example' => '^page/example$',
'page/test' => '^page/test$',
'tests' => '^tests$'
];
$patterns['HEAD'] = [

];
$patterns['POST'] = [
'login' => '^login$'
];
$patterns['PUT'] = [

];
$patterns['DELETE'] = [

];
$patterns['OPTIONS'] = [

];
$patterns['CLI'] = [
'queue/process' => '^queue/process$',
'queue/jobs/{job_id=int}/work' => '^queue/jobs/([0-9]+)/work$'
];
$named_patterns = [
'LoginPage' => 'login',
'Logout' => 'logout'
];
