<?php
namespace App\Ims\Company\Model; 
class Company {
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