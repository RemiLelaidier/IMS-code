<?php
namespace App\Ims\Employee\Model;

use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model{
    protected $table = "employee";
    protected $primaryKey = "id";
	protected $fillable = [
					'name',
					'surname',
					'gender',
					'email',
					'phone',
					'quality'
				];
}