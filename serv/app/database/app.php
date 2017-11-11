<?php

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

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
});

Manager::schema()->create('company_people', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->string('surname');
    $table->string('gender');
    $table->string('email');
    $table->string('phone');
    $table->string('quality');
});

Manager::schema()->create('unice_people', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->string('surname');
    $table->string('gender');
    $table->string('email');
    $table->string('phone');
    $table->string('quality');
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
    $table->foreign('people')->references('company_people')->on('id');
});

Manager::schema()->create('internship', function (Blueprint $table) {
    $table->increments('id');
    $table->dateTime('start');
    $table->dateTime('end');
    $table->string('address');
    $table->longText('contract');
    $table->longText('endorsement_1')->nullable();
    $table->longText('endorsement_2')->nullable();
    $table->string('notes');
});

Manager::schema()->create('convention', function (Blueprint $table) {
    $table->increments('id');
    $table->unsignedInteger('student_id');
    $table->unsignedInteger('company_id');
    $table->unsignedInteger('internship_id');
    $table->unsignedInteger('unice_people');
    $table->unsignedInteger('company_people');
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
    $table->foreign('unice_people')->references('id')->on('unice_people');
    $table->foreign('company_people')->references('id')->on('company_people');
});