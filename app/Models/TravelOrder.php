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
        'divisionhead_approved',
        'divisionhead_approved_at',
        'vp_approved',
        'vp_approved_at',
        'president_approved',
        'president_approved_at',
        'status',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'head_approved' => 'boolean',
        'divisionhead_approved' => 'boolean',
        'vp_approved' => 'boolean',
        'president_approved' => 'boolean',
        'head_approved_at' => 'datetime',
        'divisionhead_approved_at' => 'datetime',
        'vp_approved_at' => 'datetime',
        'president_approved_at' => 'datetime',
    ];

    protected $attributes = [
        'head_approved' => null,
        'divisionhead_approved' => null,
        'vp_approved' => null,
        'president_approved' => null,
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
        // if president_approved = 1, remarks = Approved
        if ($this->president_approved) {
            return 'Approved';
        }
        
        // if vp_approved = 1 (for VP's own requests), remarks = For President approval
        if ($this->vp_approved && $this->employee->is_vp) {
            return 'For President approval';
        }
        
        // if vp_approved = 1 (for other requests), remarks = Approved
        if ($this->vp_approved) {
            return 'Approved';
        }
        
        // if divisionhead_approved = 1, remarks = For VP approval
        if ($this->divisionhead_approved) {
            return 'For VP approval';
        }
        
        // if head_approved = 1 (for regular employee requests), remarks = For VP approval
        if ($this->head_approved && !$this->employee->is_head) {
            return 'For VP approval';
        }
        
        // if divisionhead_approved is null (not yet processed by division head), remarks = Pending
        if (is_null($this->divisionhead_approved) && $this->employee->is_head && !$this->employee->is_divisionhead) {
            return 'Pending';
        }
        
        // if head_approved is null (not yet processed by head), remarks = Pending
        if (is_null($this->head_approved) && !$this->employee->is_head) {
            return 'Pending';
        }
        
        // For division heads, if no approval yet, remarks = Pending
        if ($this->employee->is_divisionhead && is_null($this->vp_approved)) {
            return 'Pending';
        }
        
        // For VPs, if no approval yet, remarks = Pending
        if ($this->employee->is_vp && is_null($this->president_approved)) {
            return 'Pending';
        }
        
        // if president_approved = 0 (rejected by President), remarks = Cancelled
        if (!is_null($this->president_approved) && !$this->president_approved) {
            return 'Cancelled';
        }
        
        // if vp_approved = 0 (rejected by VP for other requests), remarks = Cancelled
        if (!is_null($this->vp_approved) && !$this->vp_approved && !$this->employee->is_vp) {
            return 'Cancelled';
        }
        
        // if vp_approved = 0 (rejected by VP for VP's own requests), remarks = Cancelled
        if (!is_null($this->vp_approved) && !$this->vp_approved && $this->employee->is_vp) {
            return 'Cancelled';
        }
        
        // if divisionhead_approved = 0 (rejected by division head), remarks = Cancelled
        if (!is_null($this->divisionhead_approved) && !$this->divisionhead_approved) {
            return 'Cancelled';
        }
        
        // if head_approved = 0 (rejected by head), remarks = Cancelled
        if (!is_null($this->head_approved) && !$this->head_approved) {
            return 'Cancelled';
        }
        
        return 'Pending';
    }
}