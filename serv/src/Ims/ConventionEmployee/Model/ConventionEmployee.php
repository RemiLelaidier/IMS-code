<?php
namespace App\Ims\ConventionEmployee\Model;

use Illuminate\Database\Eloquent\Model;

class ConventionEmployee extends Model{
    protected $table = "convention_employee";
    protected $primaryKey = "id";
	protected $fillable = [
					'convention_role'
				];
}