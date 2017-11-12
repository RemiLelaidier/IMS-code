<?php
namespace App\Core\Generator;

use Illuminate\Database\Schema\Blueprint;

class Generator {

    /**
     * Generate
     * @param bool $withModel
     * @param bool $withController
     * @param bool $overwrite
     * @param array $options
     */
    static function generate(bool $withModel, bool $withController, array $options = [], bool $overwrite = false){
        if ($withController){
            self::generateController($options['table'], $overwrite);
        }
        if ($withModel){
            self::generateModel($options['table'], $options['primaryKey'], $options['fillables'], $overwrite);
        }
    }

    /**
     * @param Blueprint $table
     * @param bool $overwrite
     */
    static function generateController(Blueprint $table, bool $overwrite){
        /**
         * @var Blueprint $table
         */
        $tableName = self::_toCamel($table);
        $className = $tableName . "Controller";

        $startController = <<<TAG
<?php
    
namespace App\Ims\\$tableName\Controller;
    
use App\Core\Controller\Controller;
use Slim\Http\Request;
use Slim\Http\Response;
    
class $className  extends Controller {
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
        self::createFile($tableName, $startController, false, $overwrite);
    }

    /**
     * @param Blueprint $table
     * @param string $primaryKey
     * @param array $base
     * @param bool $overwrite
     * @return string
     */
    static function generateModel(Blueprint $table, string $primaryKey, array $base, bool $overwrite){
        /**
         * @var Blueprint $table
         */
        $rawTableName = $table->getTable();

        $tableName = self::_toCamel($table);

        $startModel = <<<TAG
<?php
namespace App\Ims\\$tableName\Model;

use Illuminate\Database\Eloquent\Model;

class $tableName extends Model{
    protected \$table = "$rawTableName";
    protected \$primaryKey = "$primaryKey";
TAG;

        $fillables = [];
        foreach($table->getColumns() as $column){
            if(in_array($column->getAttributes()['name'], $base)){
                $fillables[] = $column;
            }
        }

        $startFillables = "\n\tprotected \$fillable = [\n";
        $end = ",\n";
        
        foreach($fillables as $k => $fillable){
            $startFillables .= "\t\t\t\t\t'" . $fillable->getAttributes()['name'] . "'";
            if($k !== count($fillables)-1){
                $startFillables .= $end;
            }
        }

        $startFillables .= "\n\t\t\t\t];";
        $startModel .= $startFillables;
        $endModel = $startModel;
        $endModel .= "\n}";

        return self::createFile($tableName, $endModel, true, $overwrite);
    }

    /**
     * @param      $fileName
     * @param      $endModel
     * @param bool $isModel
     * @param      $overwrite
     *
     * @return string
     */
    static function createFile($fileName, $endModel, bool $isModel, $overwrite):string{

        $original = $fileName;

        $fileName = $isModel ? $fileName : $fileName . "Controller";

        $namespace = $isModel ? "Model" :"Controller";
        $fileName = ucfirst($fileName);
        $dirName = __DIR__ . "/../../Ims/$original/$namespace";
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

    /**
     * @param Blueprint $table
     *
     * @return string
     */
    public static function _toCamel (Blueprint $table): string {

        $rawTableName = $table->getTable();

        $camel = strpos($rawTableName, "_");

        if ($camel !== false) {
            $charToReplace = substr($rawTableName, $camel + 1, 1);
            $stringToReplace = "_" . $charToReplace;
            $replace = ucfirst($charToReplace);
            $tableName = ucfirst(str_replace($stringToReplace, $replace, $rawTableName));
        }
        else {
            $tableName = ucfirst($table->getTable());
        }

        return $tableName;
    }
}