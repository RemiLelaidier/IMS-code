<?php
namespace App\Ims\Student\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $surname
 * @property string $promotion
 * @property string $gender
 * @property string $ss
 * @property string $num
 * @property string $email
 * @property string $dob
 * @property string $phone
 * @property string $address
 * @property string $insurance
 * @property string $police
*/
class StudentModel extends Model{
    protected $table = "student";
    protected $primaryKey = "id";
	protected $fillables = [
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