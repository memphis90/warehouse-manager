<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Facades\RequestItemFacade;

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


    public static function getAvailableQuantityForPeriod($itemId, $startDate, $endDate, $excludeRequestId = null)
    {
        $item = self::find($itemId);
        
        if (!$item) {
            return [
                'available' => 0,
                'error' => 'Item not found'
            ];
        }
        
        // Query overlapping request items
        $overlappingItems = RequestItemFacade::where('item_id', $itemId)
                ->whereHas('request', function($requestQuery) use ($excludeRequestId) {
                    $requestQuery->whereHas('status', function($statusQuery) {
                        $statusQuery->whereIn('name', ['approved', 'delivered']);
                    });
                    
                    if ($excludeRequestId) {
                        $requestQuery->where('id', '!=', $excludeRequestId);
                    }
                })
                ->where('needed_from', '<=', $endDate)
                ->where('needed_to', '>=', $startDate)
                ->with(['request.status'])
                ->get();
            
            $occupiedQuantity = $overlappingItems->sum('quantity');
        
           return [
                'available' => max(0, $item->quantity - $occupiedQuantity),
                'total_quantity' => $item->quantity,
                'occupied_quantity' => $occupiedQuantity,
                'overlapping_bookings' => $overlappingItems->map(function($requestItem) {
                    return [
                        'request_id' => $requestItem->request_id,
                        'quantity' => $requestItem->quantity,
                        'from' => $requestItem->needed_from,
                        'to' => $requestItem->needed_to,
                        'status' => $requestItem->request->status->name ?? 'unknown'
                    ];
                })
            ];
        }











}