<?php
namespace App\Ims\Employee\Model;

use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model{
    protected $table = "employee";
    protected $primaryKey = "id";
	protected $fillables = [
					'name',
					'surname',
					'gender',
					'email',
					'phone',
					'quality'
				];
}