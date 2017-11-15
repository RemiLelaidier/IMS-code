<?php
namespace App\Ims\Convention\Model;

use Illuminate\Database\Eloquent\Model;

class ConventionModel extends Model{
    protected $table = "convention";
    protected $primaryKey = "id";
	protected $fillables = [
					'receipt_from_student',
					'company_validate',
					'school_validate',
					'student_validate',
					'unice_validate',
					'send_to_unice',
					'return_from_unice',
					'notes'
				];
}