<?php
namespace App\Ims\Internship\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $start
 * @property string $end
 * @property string $address
 * @property string $working_hours
 * @property string $weekly_duration
 * @property string $extra_work
 * @property string $income
 * @property string $payement
 * @property string $advantages
 * @property string $subject
 * @property longText $detail
 * @property longText $contract
 * @property longText $endorsement_1
 * @property longText $endorsement_2
 * @property string $notes
*/
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
					'subject',
					'detail',
					'contract',
					'endorsement_1',
					'endorsement_2',
					'notes'
				];
}