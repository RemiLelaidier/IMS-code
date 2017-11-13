<?php
namespace App\Ims\Internship\Model;

use Illuminate\Database\Eloquent\Model;

class InternshipModel extends Model{
    protected $table = "internship";
    protected $primaryKey = "id";
	protected $fillables = [
					'start',
					'end',
					'address',
					'working_hours',
					'weekly_duration',
					'extra_work',
					'income',
					'payement',
					'advantages',
					'detail',
					'contract',
					'endorsement_1',
					'endorsement_2',
					'notes'
				];
}