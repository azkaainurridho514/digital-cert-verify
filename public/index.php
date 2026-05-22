<?php

define('LARAVEL_START', microtime(true));

require __DIR__.'/../digital-cert-verify-olc/vendor/autoload.php';

$app = require_once __DIR__.'/../digital-cert-verify-olc/bootstrap/app.php';

$app->bind('path.public', function() {
    return __DIR__;
});

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);