<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'item_id',
        'quantity',
        'needed_from',
        'name',
        'needed_to',
        'notes'
    ];

    protected $casts = [
        'needed_from' => 'datetime',
        'needed_to' => 'datetime',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}