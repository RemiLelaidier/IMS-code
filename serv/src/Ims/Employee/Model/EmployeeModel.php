<?php
namespace App\Ims\Employee\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $surname
 * @property string $gender
 * @property string $email
 * @property string $phone
 * @property string $quality
 * @property integer $company_id
*/
class EmployeeModel extends Model{
    protected $table = "employee";
    protected $primaryKey = "id";
	protected $fillables = [
					'name',
					'surname',
					'gender',
					'email',
					'phone',
					'quality',
					'company_id'
				];
}