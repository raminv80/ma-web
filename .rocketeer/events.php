<?php
use Rocketeer\Facades\Rocketeer;

Rocketeer::before('dependencies', function ($task) {
    return [
        $task->runInFolder('shared', 'touch .env'),
        $task->runInFolder('shared', 'mkdir -p .ckfinder'),
    ];
});

Rocketeer::after('dependencies', function ($task) {
    //return $task->runForCurrentRelease('phing');
});

//Rocketeer::after('dependencies', function ($task) {
//    return $task->runForCurrentRelease(
//        [
//            'touch web/.htaccess',
//            'cd web/app/themes/example/ && composer install'
//        ]
//    );
//});

