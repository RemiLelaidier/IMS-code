<?php
namespace App\Ims\Student\Model;

use Illuminate\Database\Eloquent\Model;

class StudentModel extends Model{
    protected $table = "student";
    protected $primaryKey = "id";
	protected $fillable = [
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
}