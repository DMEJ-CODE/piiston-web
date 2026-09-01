<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Services\Identity\AuthService;
use Illuminate\Contracts\Console\Kernel;

$auth = app(AuthService::class);
try {
    $res = $auth->register([
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test_owner_php@piiston.com',
        'password' => 'password',
        'phone' => '1122334455',
        'role' => 'VEHICLE_OWNER',
        'country_id' => 1,
        'status' => 'active',
    ]);
    echo 'Success! Roles assigned: '.$res['user']->roles->pluck('name')->implode(', ')."\n";
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage()."\n";
}
