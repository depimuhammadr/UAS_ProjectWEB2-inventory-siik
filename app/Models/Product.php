<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'branch_id',
        'description',
        'barcode',
        'stock',
        'available_stock',
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->barcode)) {
                $branch = Branch::find($product->branch_id);
                $category = Category::find($product->category_id);
                
                $branchCode = $branch ? strtoupper($branch->code) : 'GEN';
                $categoryCode = $category ? strtoupper($category->code) : 'GEN';
                
                $randomNum = rand(10000, 99999);
                $product->barcode = "BRG-{$branchCode}-{$categoryCode}-{$randomNum}";
                
                while (static::where('barcode', $product->barcode)->exists()) {
                    $randomNum = rand(10000, 99999);
                    $product->barcode = "BRG-{$branchCode}-{$categoryCode}-{$randomNum}";
                }
            }
            
            if (!isset($product->available_stock)) {
                $product->available_stock = $product->stock;
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('stock')) {
                $diff = $product->stock - $product->getOriginal('stock');
                $product->available_stock = max(0, $product->available_stock + $diff);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }
}
