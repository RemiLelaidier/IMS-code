<?php
namespace App\Ims\Unice\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $surname
 * @property string $gender
 * @property string $email
 * @property string $phone
 * @property string $quality
*/
class UniceModel extends Model{
    protected $table = "unice";
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