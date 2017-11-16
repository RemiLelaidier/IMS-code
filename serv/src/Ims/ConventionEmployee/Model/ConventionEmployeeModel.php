<?php
namespace App\Ims\ConventionEmployee\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $convention_id
 * @property integer $employee_id
 * @property string $convention_role
*/
class ConventionEmployeeModel extends Model{
    protected $table = "convention_employee";
    protected $primaryKey = "id";
	protected $fillables = [
					'convention_id',
					'employee_id',
					'convention_role'
				];
}