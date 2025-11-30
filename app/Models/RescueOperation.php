<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RescueOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'calamity_affected_household_id',
        'rescuer_type',
        'rescuer_id',
        'rescue_time',
        'evacuation_center_id',
        'ambulance_vehicle',
        'notes',
    ];

    protected $casts = [
        'rescue_time' => 'datetime',
    ];

    public function affectedHousehold()
    {
        return $this->belongsTo(CalamityAffectedHousehold::class, 'calamity_affected_household_id');
    }

    public function rescuer()
    {
        return $this->belongsTo(ResponseTeamMember::class, 'rescuer_id');
    }

    public function evacuationCenter()
    {
        return $this->belongsTo(EvacuationCenter::class, 'evacuation_center_id');
    }
}