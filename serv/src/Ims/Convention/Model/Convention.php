<?php
namespace App\Ims\Convention\Model; 
class Convention {
    protected $table = "convention";
    protected $primaryKey = "id";
	protected $fillable = [
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