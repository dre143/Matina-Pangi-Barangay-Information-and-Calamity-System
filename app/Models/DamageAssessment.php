<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'calamity_id',
        'household_id',
        'damage_level',
        'estimated_cost',
        'description',
        'photo_path',
        'assessed_at',
        'assessed_by'
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'assessed_at' => 'datetime'
    ];

    public function calamity()
    {
        return $this->belongsTo(Calamity::class);
    }

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}