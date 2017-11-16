<?php
namespace App\Ims\Company\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $address
 * @property string $website
 * @property string $phone
 * @property string $email
 * @property string $nationality
 * @property string $director_name
 * @property string $director_surname
 * @property string $director_email
 * @property string $director_phone
 * @property string $director_quality
 * @property string $director_gender
 * @property string $siren
 * @property string $notes
*/
class CompanyModel extends Model{
    protected $table = "company";
    protected $primaryKey = "id";
	protected $fillables = [
					'name',
					'address',
					'website',
					'phone',
					'email',
					'nationality',
					'director_name',
					'director_surname',
					'director_email',
					'director_phone',
					'director_quality',
					'director_gender',
					'siren',
					'notes'
				];
}