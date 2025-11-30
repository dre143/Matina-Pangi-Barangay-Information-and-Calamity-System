<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseTeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'skills',
        'calamity_id',
        'evacuation_center_id',
        'assignment_notes'
    ];

    protected $casts = [
        'skills' => 'array'
    ];

    public function calamity()
    {
        return $this->belongsTo(Calamity::class);
    }

    public function evacuationCenter()
    {
        return $this->belongsTo(EvacuationCenter::class);
    }

    public function rescueOperations()
    {
        return $this->hasMany(RescueOperation::class, 'rescuer_id');
    }
}