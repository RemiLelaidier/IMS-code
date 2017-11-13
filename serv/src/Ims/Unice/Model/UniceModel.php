<?php
namespace App\Ims\Unice\Model;

use Illuminate\Database\Eloquent\Model;

class UniceModel extends Model{
    protected $table = "unice";
    protected $primaryKey = "id";
	protected $fillable = [
					'name',
					'surname',
					'gender',
					'email',
					'phone',
					'quality'
				];
}