<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PwdSupport extends Model
{
    use HasFactory;

    protected $table = 'pwd_support';

    protected $fillable = [
        'resident_id',
        'disability_type',
        'disability_level',
        'pwd_id_number',
        'pwd_id_expiry',
        'date_registered',
        'disability_description',
        'assistance_received',
        'medical_needs',
        'notes',
        'assistive_devices_needed',
        'support_services_received',
        'caregiver_name',
        'caregiver_contact',
    ];

    protected $casts = [
        'pwd_id_expiry' => 'date',
        'date_registered' => 'date',
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
    public function scopeByLevel($query, $level)
    {
        return $query->where('disability_level', $level);
    }

    public function scopeExpiringSoon($query)
    {
        return $query->whereNotNull('pwd_id_expiry')
                    ->whereBetween('pwd_id_expiry', [now(), now()->addDays(30)]);
    }

    /**
     * Check if PWD ID is expired
     */
    public function isExpired()
    {
        if (!$this->pwd_id_expiry) {
            return false;
        }
        
        return $this->pwd_id_expiry->isPast();
    }

    /**
     * Check if PWD ID is expiring soon (within 30 days)
     */
    public function isExpiringSoon()
    {
        if (!$this->pwd_id_expiry) {
            return false;
        }
        
        return $this->pwd_id_expiry->diffInDays(now()) <= 30 && $this->pwd_id_expiry->isFuture();
    }
}
