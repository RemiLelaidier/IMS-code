<?php

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;
use App\Core\Generator\EloquentGenerator;

Manager::schema()->create('student', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->string('surname');
    $table->string('promotion');
    $table->string('gender');
    $table->string('ss');
    $table->string('num');
    $table->string('email');
    $table->string('dob');
    $table->string('phone');
    $table->string('address');
    $table->string('insurance');
    $table->string('police');

    $fillables = [
        'name',
        'surname',
        'promotion',
        'gender',
        'ss',
        'num',
        'email',
        'dob',
        'phone',
        'address',
        'insurance',
        'police'
    ];

    EloquentGenerator::generate(true, true, [
        'fillables' => $fillables,
        'primaryKey' => 'id',
        'table' => $table
    ], true);
});

Manager::schema()->create('company', function (Blueprint $table) {
    $table->increments('id');
    $table->unsignedInteger('people');
    $table->string('name');
    $table->string('address');
    $table->string('website');
    $table->string('phone');
    $table->string('email');
    $table->string('nationality');
    $table->string('siren');
    $table->string('notes');
    $table->foreign('people')->references('employee')->on('id');

    $fillables = [
        'name',
        'address',
        'website',
        'phone',
        'email',
        'nationality',
        'siren',
        'notes'
    ];

    EloquentGenerator::generate(true, true, [
        'fillables' => $fillables,
        'primaryKey' => 'id',
        'table' => $table
    ], true);
});

Manager::schema()->create('employee', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->string('surname');
    $table->string('gender');
    $table->string('email');
    $table->string('phone');
    $table->string('quality');
    $table->unsignedInteger('company_id');
    $table->foreign('company_id')->references('company')->on('id');

    $fillables = [
        'name',
        'surname',
        'gender',
        'email',
        'phone',
        'quality'
    ];

    EloquentGenerator::generate(true, true, [
        'fillables' => $fillables,
        'primaryKey' => 'id',
        'table' => $table
    ], true);
});



Manager::schema()->create('internship', function (Blueprint $table) {
    $table->increments('id');
    $table->dateTime('start');
    $table->dateTime('end');
    $table->string('address');
    $table->string('working_hours');
    $table->string('weekly_duration');
    $table->string('extra_work');
    $table->string('income');
    $table->string('payement');
    $table->string('advantages');
    $table->string('subject');
    $table->string('detail');
    $table->longText('contract');
    $table->longText('endorsement_1')->nullable();
    $table->longText('endorsement_2')->nullable();
    $table->string('notes');

    $fillables = [
        'start',
        'end',
        'address',
        'working_hours',
        'weekly_duration',
        'extra_work',
        'income',
        'payement',
        'advantages',
        'detail',
        'contract',
        'endorsement_1',
        'endorsement_2',
        'notes',
    ];

    EloquentGenerator::generate(true, true, [
        'fillables' => $fillables,
        'primaryKey' => 'id',
        'table' => $table
    ], true);
});

Manager::schema()->create('convention', function (Blueprint $table) {
    $table->increments('id');
    $table->timestamps();
    $table->unsignedInteger('student_id');
    $table->unsignedInteger('company_id');
    $table->unsignedInteger('internship_id');
    $table->dateTime('receipt_from_student')->nullable();
    $table->dateTime('company_validate')->nullable();
    $table->dateTime('school_validate')->nullable();
    $table->dateTime('student_validate')->nullable();
    $table->dateTime('unice_validate')->nullable();
    $table->dateTime('send_to_unice')->nullable();
    $table->dateTime('return_from_unice')->nullable();
    $table->string('notes');
    $table->foreign('student_id')->references('id')->on('student');
    $table->foreign('company_id')->references('id')->on('company');
    $table->foreign('internship_id')->references('id')->on('internship');

    $fillables = [
        'receipt_from_student',
        'company_validate',
        'school_validate',
        'student_validate',
        'unice_validate',
        'send_to_unice',
        'return_from_unice',
        'notes'
    ];

    EloquentGenerator::generate(true, false, [
        'fillables' => $fillables,
        'primaryKey' => 'id',
        'table' => $table
    ], true);

});

Manager::schema()->create('unice', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->string('surname');
    $table->string('gender');
    $table->string('email');
    $table->string('phone');
    $table->string('quality');
    $table->unsignedInteger('convention_id');
    $table->foreign('convention_id')->references('id')->on('convention');

    $fillables = [
        'name',
        'surname',
        'gender',
        'email',
        'phone',
        'quality'
    ];

    EloquentGenerator::generate(true, true, [
        'fillables' => $fillables,
        'primaryKey' => 'id',
        'table' => $table
    ], true);
});

Manager::schema()->create('convention_unice', function (Blueprint $table) {
    $table->unsignedInteger('convention_id');
    $table->unsignedInteger('unice_id');
    $table->string('convention_role');
    $table->primary(['convention_id', 'unice_id']);
    $table->foreign('convention_id')->references('id')->on('convention');
    $table->foreign('unice_id')->references('id')->on('unice');

    $fillables = [
        'convention_role'
    ];

    EloquentGenerator::generate(true, false, [
        'fillables' => $fillables,
        'primaryKey' => 'id',
        'table' => $table
    ], true);
});

Manager::schema()->create('convention_employee', function (Blueprint $table) {
    $table->unsignedInteger('convention_id');
    $table->unsignedInteger('employee_id');
    $table->string('convention_role');
    $table->primary(['convention_id', 'employee_id']);
    $table->foreign('convention_id')->references('id')->on('convention');
    $table->foreign('employee_id')->references('id')->on('employee');

    $fillables = [
        'convention_role'
    ];

    EloquentGenerator::generate(true, false, [
        'fillables' => $fillables,
        'primaryKey' => 'id',
        'table' => $table
    ], true);
});
