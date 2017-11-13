<?php
namespace App\Ims\Company\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model{
    protected $table = "company";
    protected $primaryKey = "id";
	protected $fillable = [
					'name',
					'address',
					'website',
					'phone',
					'email',
					'nationality',
					'siren',
					'notes'
				];
}