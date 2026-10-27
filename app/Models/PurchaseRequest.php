<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pr_number',
        'company_id',
        'requester_name',
        'department',
        'request_date',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'request_date' => 'date:Y-m-d',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseRequestItem::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function getTotalEstimatedPriceAttribute(): float
    {
        return (float) $this->items->sum(function ($item) {
            return $item->quantity * $item->estimated_price;
        });
    }

    /**
     * Generate next PR number in format: TSPM10000/{YEAR}/{MONTH}/PR{0001}
     */
    public static function generateNextNumber(?string $date = null): string
    {
        $targetDate = $date ? \Carbon\Carbon::parse($date) : now();
        $year = $targetDate->format('Y');
        $month = $targetDate->format('m');

        $maxSeq = static::where('pr_number', 'like', "TSPM10000/{$year}/{$month}/PR%")
            ->pluck('pr_number')
            ->map(function ($num) {
                return preg_match('/\/PR(\d+)$/', $num, $m) ? (int) $m[1] : 0;
            })
            ->max();

        if ($maxSeq === null || $maxSeq === false) {
            $maxSeq = static::where('pr_number', 'like', "TSPM10000/%/PR%")
                ->pluck('pr_number')
                ->map(function ($num) {
                    return preg_match('/\/PR(\d+)$/', $num, $m) ? (int) $m[1] : 0;
                })
                ->max() ?? 0;
        }

        return sprintf('TSPM10000/%s/%s/PR%04d', $year, $month, $maxSeq + 1);
    }
}
