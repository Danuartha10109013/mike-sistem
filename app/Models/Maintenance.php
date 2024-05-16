<?php

namespace App\Models;

use App\MaintenanceStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    protected $casts = [
        'status' => MaintenanceStatus::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function approved(): bool
    {
        $this->status = MaintenanceStatus::Approved;
        $this->approved_by = auth()->id();
        $this->approval_date = now();
        return $this->save();
    }

    public function isPending(): bool
    {
        return $this->status === MaintenanceStatus::Pending;
    }

    public function isApproved(): bool
    {
        return $this->status === MaintenanceStatus::Approved;
    }

    public function isRejected(): bool
    {
        return $this->status === MaintenanceStatus::Rejected;
    }

    public function isCompleted(): bool
    {
        return $this->status === MaintenanceStatus::Completed;
    }

    public function scopeApprovedAllTime(Builder $builder): Builder
    {
        return $builder->where('status', MaintenanceStatus::Approved);
    }

    public function scopeApprovedThisMonth(Builder $builder): Builder
    {
        return $builder->where('status', MaintenanceStatus::Approved)
            ->where('approval_date', '>=', now()->startOfMonth());
    }

    public function scopeApprovedLastMonth(Builder $builder): Builder
    {
        return $builder->where('status', MaintenanceStatus::Approved)
            ->where('approval_date', '>=', now()->startOfMonth()->subMonth())
            ->where('approval_date', '<', now()->subMonth()->endOfMonth());
    }

}
