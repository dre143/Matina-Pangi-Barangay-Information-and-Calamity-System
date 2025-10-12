<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeniorHealth extends Model
{
    use HasFactory;

    protected $table = 'senior_health';

    protected $fillable = [
        'resident_id',
        'senior_id_number',
        'pension_type',
        'pension_amount',
        'health_conditions',
        'medications',
        'mobility_status',
        'emergency_medical_info',
    ];

    protected $casts = [
        'pension_amount' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Scopes
     */
    public function scopeByMobility($query, $status)
    {
        return $query->where('mobility_status', $status);
    }

    public function scopeWithPension($query)
    {
        return $query->whereNotNull('pension_type');
    }

    public function scopeBedridden($query)
    {
        return $query->where('mobility_status', 'bedridden');
    }

    /**
     * Check if senior needs assistance
     */
    public function needsAssistance()
    {
        return in_array($this->mobility_status, ['assisted', 'wheelchair', 'bedridden']);
    }
}
