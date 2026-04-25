<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'cw_patrol_schedule';
    protected $primaryKey = 'patrolNr';
    public $timestamps = false;

    protected $fillable = [
        'patrolDate',
        'patrolDescription',
        'SuperUserNr',
        'patrol_status',
    ];

    protected $casts = [];

    /**
     * Get volunteers assigned to this patrol
     */
    public function volunteers()
    {
        return $this->belongsToMany(
            User::class,
            'cw_patrol_roster',           
            'patrolNr',                   
            'volunteer_ID_Nr',            
            'patrolNr',                   
            'UserNr'                      
        );
    }

    /**
     * Get supervisor for this patrol
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'SuperUserNr', 'UserNr');
    }
}
