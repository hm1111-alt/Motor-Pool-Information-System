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
        'emp_position_id',
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
     * Get the position associated with the travel order.
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(EmpPosition::class, 'emp_position_id');
    }

    /**
     * Get remarks based on approval status (not stored in database)
     */
    public function getRemarksAttribute(): string
    {
        // For presidents, if president_approved = 1, remarks = Approved (automatically)
        if ($this->employee->is_president && $this->president_approved) {
            return 'Approved';
        }
        
        // For presidents, if status = approved, remarks = Approved (automatically)
        if ($this->employee->is_president && $this->status === 'approved') {
            return 'Approved';
        }
        
        // if president_approved = 1, remarks = Approved
        if ($this->president_approved) {
            return 'Approved';
        }
        
        // if vp_approved = 1 (for VP's own requests), remarks = For President approval
        if ($this->vp_approved && $this->employee->is_vp) {
            return 'For President approval';
        }
        
        // if vp_approved = 1 (for division head requests), remarks = For President approval
        if ($this->vp_approved && $this->employee->is_divisionhead) {
            return 'For President approval';
        }
        
        // if vp_approved = 1 (for other requests), remarks = Approved
        if ($this->vp_approved) {
            return 'Approved';
        }
        
        // if divisionhead_approved = 1, remarks = For VP approval (for unit head requests) or Approved (for regular employee requests)
        if ($this->divisionhead_approved) {
            // For regular employees, if division head approved, it's fully approved
            if (!$this->employee->is_head && !$this->employee->is_divisionhead && !$this->employee->is_vp && !$this->employee->is_president) {
                return 'Approved';
            } else {
                // For unit heads, if division head approved, it's for VP approval
                return 'For VP approval';
            }
        }
        
        // if head_approved = 1 but divisionhead_approved is null (for regular employee requests), remarks = For Division Head approval
        if ($this->head_approved && !$this->employee->is_head && is_null($this->divisionhead_approved)) {
            return 'For Division Head approval';
        }
        
        // if divisionhead_approved is null (not yet processed by division head for head's requests), remarks = Pending
        if (is_null($this->divisionhead_approved) && $this->employee->is_head && !$this->employee->is_divisionhead) {
            return 'Pending';
        }
        
        // if divisionhead_approved is null (not yet processed by division head for regular employee's requests), remarks = For Division Head approval
        if (is_null($this->divisionhead_approved) && !$this->employee->is_head && $this->head_approved) {
            return 'For Division Head approval';
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