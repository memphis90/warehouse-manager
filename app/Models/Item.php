<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'category_id', 
        'quantity', 
        'status', 
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function requestItems()
    {
        return $this->hasMany(RequestItem::class);
    }

    // Metodo per calcolare quantità disponibile 
    public function getAvailableQuantityAttribute()
    {
        $activeRequestsQuantity = $this->requestItems()
        ->whereHas('request', function($query) {
            $query->whereHas('status', function($q) {
                $q->whereIn('name', ['approved', 'delivered']); // stati che "occupano" l'item
            });
        })
        ->sum('quantity');

    return $this->quantity - $activeRequestsQuantity;
    }

}
    