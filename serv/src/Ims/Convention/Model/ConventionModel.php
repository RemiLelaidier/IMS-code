<?php
namespace App\Ims\Convention\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property timestamp $receipt_from_student
 * @property timestamp $company_validate
 * @property timestamp $school_validate
 * @property timestamp $student_validate
 * @property timestamp $unice_validate
 * @property timestamp $send_to_unice
 * @property timestamp $return_from_unice
 * @property string $notes
*/
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