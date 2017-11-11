<?php
namespace App\Ims\Student\Model; 
class Student {
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
					'police',
				];
}