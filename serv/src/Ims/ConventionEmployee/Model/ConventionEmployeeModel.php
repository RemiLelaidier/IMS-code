<?php
namespace App\Ims\ConventionEmployee\Model;

use Illuminate\Database\Eloquent\Model;

class ConventionEmployeeModel extends Model{
    protected $table = "convention_employee";
    protected $primaryKey = "id";
	protected $fillables = [
					'convention_role'
				];
}