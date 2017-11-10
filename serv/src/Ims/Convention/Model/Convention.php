<?php

namespace App\Ims\Convention\Model;

class Convention
{
    protected $table = 'convention';

    protected $primaryKey = 'id';

    protected $fillable = [
        'raw_data',
        'student_id',
        'entreprise_id'
    ];
}
