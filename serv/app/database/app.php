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
    generate(true, true, [
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
    generate(true, true, [
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
    generate(true, true, [
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
    generate(true, true, [
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
    generate(true, false, [
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
    generate(true, true, [
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
    generate(true, false, [
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
    generate(true, false, [
        'fillables' => $fillables,
        'primaryKey' => 'id',
        'table' => $table
    ], true);
});

/**
 * Generate
 * @param bool $withModel
 * @param bool $withController
 * @param bool $overwrite
 * @param array $options
 */
function generate(bool $withModel, bool $withController, array $options = [], bool $overwrite = false){
    if ($withController){
        generateController($options['table'], $overwrite);
    }
    if ($withModel){
        generateModel($options['table'], $options['primaryKey'], $options['fillables'], $overwrite);
    }
}

/**
 * @param Blueprint $table
 * @param bool $overwrite
 */
function generateController(Blueprint $table, bool $overwrite){
    /**
     * @var Illuminate\Database\Schema\Blueprint $table
     */
    $tableName = ucfirst($table->getTable());
    $className = $tableName . "Controller";

    $startController = <<<TAG
<?php
    
namespace App\Ims\\$tableName\Controller;
    
use App\Core\Controller\Controller;
use Slim\Http\Request;
use Slim\Http\Response;
    
class $className  {
    /**
     * @param Request  \$request
     * @param Response \$response
     *
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function dummy(Request \$request, Response \$response)
    {
        // Get raw data from stream
        \$data = \$request->getBody()->getContents();
        // Decode JSON from raw data
        \$decoded = json_decode(\$data, true);
        // Log 
        \$this->logger->debug('New data on ' . get_class(\$this) . ":submit", [
            'data' => \$data
        ]);
        // Return response
        return \$this->ok(\$response, [
            'data' => \$decoded
        ]);
    }
}
TAG;
    createFile($tableName, $startController, false, $overwrite);
}

/**
 * @param Blueprint $table
 * @param string $primaryKey
 * @param array $base
 * @param bool $overwrite
 * @return string
 */
function generateModel(Blueprint $table, string $primaryKey, array $base, bool $overwrite){
    /**
     * @var Illuminate\Database\Schema\Blueprint $table
     */
    $tableName = ucfirst($table->getTable());

    $startModel = "<?php \n
                    namespace App\Ims\\$tableName\Model; \n
                    class $tableName {\n
                    protected \$table = \"$tableName\";\n
                    protected \$primaryKey = \"$primaryKey\";\n\n";

    $fillables = [];
    foreach($table->getColumns() as $column){
        if(in_array($column->getAttributes()['name'], $base)){
            $fillables[] = $column;
        }
    }

    $startFillables = 'protected $fillable = [';
    foreach($fillables as $fillable){
        $startFillables .= "'" . $fillable->getAttributes()['name'] . "',";
    }

    $startFillables = substr($startFillables, 0, -1);

    $startFillables .= "];";
    $startModel .= $startFillables;
    $endModel = $startModel;
    $endModel .= "}";

    return createFile($tableName, $endModel, true, $overwrite);
}

/**
 * @param $tableName
 * @param $endModel
 * @param bool $isModel
 * @return string
 */
function createFile($fileName, $endModel, bool $isModel, $overwrite):string{

    $original = $fileName;

    $fileName = $isModel ? $fileName : $fileName . "Controller";

    $namespace = $isModel ? "Model" :"Controller";
    $fileName = ucfirst($fileName);
    $dirName = __DIR__ . "/../../src/Ims/$original/$namespace";
    $nextPath = "$dirName/$fileName.php";

    if(is_file($nextPath) && !$overwrite){
        return "";
    }
    if (!is_dir($dirName)) {
        mkdir($dirName, 0755, true);
    }

    if (file_put_contents($nextPath, $endModel)) {
        return "updated";
    }

    return "error";
}