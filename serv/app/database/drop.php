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
    'unice_people',
    'company',
    'company_people'
];

Manager::schema()->disableForeignKeyConstraints();
foreach ($tables as $table) {
    Manager::schema()->dropIfExists($table);
}
