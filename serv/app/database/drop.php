<?php

use Illuminate\Database\Capsule\Manager;

$tables = [
    'activations',
    'persistences',
    'reminders',
    'role_users',
    'throttle',
    'roles',
    'access_token',
    'refresh_token',
    'user',
    'student',
    'company',
    'convention',
    'internship',
    'unice',
    'company',
    'employee',
    'convention_unice',
    'convention_employee'
];

Manager::schema()->disableForeignKeyConstraints();
foreach ($tables as $table) {
    Manager::schema()->dropIfExists($table);
}
