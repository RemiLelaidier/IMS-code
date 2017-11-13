<?php
namespace App\Ims\ConventionUnice\Model;

use Illuminate\Database\Eloquent\Model;

class ConventionUniceModel extends Model{
    protected $table = "convention_unice";
    protected $primaryKey = "id";
	protected $fillable = [
					'convention_role'
				];
}