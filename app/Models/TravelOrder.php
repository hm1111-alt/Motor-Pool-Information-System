<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'destination',
        'date_from',
        'date_to',
        'departure_time',
        'purpose',
        'head_approved',
        'head_approved_at',
        'vp_approved',
        'vp_approved_at',
        'status',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'head_approved' => 'boolean',
        'vp_approved' => 'boolean',
        'head_approved_at' => 'datetime',
        'vp_approved_at' => 'datetime',
    ];

    protected $attributes = [
        'head_approved' => null,
        'vp_approved' => null,
    ];

    /**
     * Get the employee that owns the travel order.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get remarks based on approval status (not stored in database)
     */
    public function getRemarksAttribute(): string
    {
        // if vp_approved = 1, remarks = Approved
        if ($this->vp_approved) {
            return 'Approved';
        }
        
        // if head_approved = 1, remarks = For VP approval
        if ($this->head_approved) {
            return 'For VP approval';
        }
        
        // if head_approved is null (not yet processed), remarks = Pending
        if (is_null($this->head_approved)) {
            return 'Pending';
        }
        
        // if head_approved = 0 (rejected), remarks = Cancelled
        if (!$this->head_approved) {
            return 'Cancelled';
        }
        
        return 'Pending';
    }
}