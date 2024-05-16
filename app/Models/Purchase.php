<?php

namespace App\Models;

use App\PurchaseStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => PurchaseStatus::class
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
        $this->status = PurchaseStatus::Approved;
        $this->approved_by = auth()->id();
        $this->approval_date = now();
        return $this->save();
    }

    public function isPending(): bool
    {
        return $this->status === PurchaseStatus::Pending;
    }

    public function isApproved(): bool
    {
        return $this->status === PurchaseStatus::Approved;
    }

    public function isRejected(): bool
    {
        return $this->status === PurchaseStatus::Rejected;
    }

    public function isCompleted(): bool
    {
        return $this->status === PurchaseStatus::Completed;
    }

    
}
