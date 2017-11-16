<?php
namespace App\Ims\ConventionUnice\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $convention_id
 * @property integer $unice_id
 * @property string $convention_role
*/
class ConventionUniceModel extends Model{
    protected $table = "convention_unice";
    protected $primaryKey = "id";
	protected $fillables = [
					'convention_id',
					'unice_id',
					'convention_role'
				];
}