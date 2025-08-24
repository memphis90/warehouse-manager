<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ItemFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Models\Item::class;
    }


    public static function formatStatus($status)
    {
        return match($status) {
            'available' => 'Available',
            'in_use' => 'In Use',
            'maintenance' => 'Maintenance',
            'retired' => 'Retired',
            default => $status
        };
    }

     public static function formatDateTime($dateTime)
    {
        return $dateTime?->format('F j, Y \a\t g:i A') ?? 'N/A';
    }

    public static function calculateUtilization($item)
    {
        if ($item->quantity <= 0) return 0;
        $inUse = $item->quantity - ($item->available_quantity ?? $item->quantity);
        return round(($inUse / $item->quantity) * 100);
    }
}