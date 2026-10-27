<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'po_number',
        'purchase_request_id',
        'company_id',
        'vendor_id',
        'order_date',
        'status',
        'description',
        'subtotal',
        'tax',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date:Y-m-d',
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    /**
     * Recalculate totals from items.
     */
    public function recalculateTotals(float $taxRate = 0.0): void
    {
        $this->loadMissing('items');
        $subtotal = $this->items->sum('subtotal');
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax;

        $this->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);
    }

    /**
     * Generate next PO number in format: TSPM/{YEAR}/{MONTH}/PO/{0001}
     */
    public static function generateNextNumber(?string $date = null): string
    {
        $targetDate = $date ? \Carbon\Carbon::parse($date) : now();
        $year = $targetDate->format('Y');
        $month = $targetDate->format('m');

        $maxSeq = static::where('po_number', 'like', "TSPM/{$year}/{$month}/PO/%")
            ->pluck('po_number')
            ->map(function ($num) {
                return preg_match('/\/PO\/(\d+)$/', $num, $m) ? (int) $m[1] : 0;
            })
            ->max();

        if ($maxSeq === null || $maxSeq === false) {
            $maxSeq = static::where('po_number', 'like', "TSPM/%/PO/%")
                ->pluck('po_number')
                ->map(function ($num) {
                    return preg_match('/\/PO\/(\d+)$/', $num, $m) ? (int) $m[1] : 0;
                })
                ->max() ?? 0;
        }

        return sprintf('TSPM/%s/%s/PO/%04d', $year, $month, $maxSeq + 1);
    }
}
