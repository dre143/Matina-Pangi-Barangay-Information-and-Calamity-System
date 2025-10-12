<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'record_type',
        'vaccine_name',
        'date_administered',
        'health_condition',
        'medication',
        'doctor_notes',
        'next_checkup_date',
        'recorded_by',
        // Additional health information fields
        'blood_type',
        'height',
        'weight',
        'medical_conditions',
        'allergies',
        'medications',
        'emergency_contact',
        'emergency_contact_number',
        'philhealth_number',
        'notes',
    ];

    protected $casts = [
        'date_administered' => 'date',
        'next_checkup_date' => 'date',
    ];

    /**
     * Relationships
     */
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Scopes
     */
    public function scopeImmunizations($query)
    {
        return $query->where('record_type', 'immunization');
    }

    public function scopeCheckups($query)
    {
        return $query->where('record_type', 'checkup');
    }

    public function scopeMedications($query)
    {
        return $query->where('record_type', 'medication');
    }

    public function scopeConditions($query)
    {
        return $query->where('record_type', 'condition');
    }

    public function scopeUpcomingCheckups($query)
    {
        return $query->whereNotNull('next_checkup_date')
                    ->where('next_checkup_date', '>=', now())
                    ->orderBy('next_checkup_date');
    }
}
