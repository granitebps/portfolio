<?php

use Illuminate\Contracts\Console\Kernel;

require_once __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Bootstrap The Test Environment
|--------------------------------------------------------------------------
|
| You may specify console commands that execute once before your test is
| run. You are free to add your own additional commands or logic into
| this file as needed in order to help your test suite run quicker.
|
*/

$commands = [
    'config:cache',
    'event:cache',
];

$app = require __DIR__.'/../bootstrap/app.php';

$console = tap($app->make(Kernel::class))->bootstrap();

foreach ($commands as $command) {
    $console->call($command);
}

// The bootstrap above registers Laravel's error/exception handlers via HandleExceptions::bootstrap().
// These handlers persist and prevent PHPUnit's ErrorHandler from registering itself (it bails out
// when it finds an existing handler). Clean them up so PHPUnit can manage handlers per-test.
Illuminate\Foundation\Bootstrap\HandleExceptions::flushState();
