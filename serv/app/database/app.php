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

Manager::schema()->create('employee', function (Blueprint $table) {
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
    $table->foreign('people')->references('employee')->on('id');
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


    /**
     *
     */

    /**
     * @var Illuminate\Database\Schema\Blueprint $table
     */
    $tableName = $table->getTable();

    $primaryKey = "id";

    $startModel = "<?php \n
                    namespace App\Ims\/$tableName\Model; \n
                    class $tableName {\n
                    protected \$table = \"$tableName\";\n
                    protected \$primaryKey = \"$primaryKey\";";

    $fillables = [];

    $hayStackProtected = ["integer"];
    $hayStackFillables = ["receipt_from_student", 'company_validate', 'school_validate', 'student_validate', 'unice_validate', 'send_to_unice', 'return_from_unice', 'notes'];

    foreach($table->getColumns() as $column){
        /**
         * @var Illuminate\Support\Fluent $column
         */
        if(in_array($column->getAttributes()['name'], $hayStackFillables)){
            $fillables[] = $column;
        }
    }

    $startFillables = '$fillable = [';

    foreach($fillables as $fillable){
        /**
         * @var Illuminate\Support\Fluent $fillable
         */
        $startFillables .= "'" . $fillable->getAttributes()['name'] . "',";
    }

    $startFillables = substr($startFillables, 0, -1);

    $startFillables .= "];";

    $startModel .= $startFillables;

    $endModel = $startModel;

    $endModel .= "}";
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
});

Manager::schema()->create('convention_unice', function (Blueprint $table) {
    $table->unsignedInteger('convention_id');
    $table->unsignedInteger('unice_id');
    $table->string('convention_role');
    $table->primary(['convention_id', 'unice_id']);
    $table->foreign('convention_id')->references('id')->on('convention');
    $table->foreign('unice_id')->references('id')->on('unice');
});

Manager::schema()->create('convention_employee', function (Blueprint $table) {
    $table->unsignedInteger('convention_id');
    $table->unsignedInteger('employee_id');
    $table->string('convention_role');
    $table->primary(['convention_id', 'employee_id']);
    $table->foreign('convention_id')->references('id')->on('convention');
    $table->foreign('employee_id')->references('id')->on('employee');
});

