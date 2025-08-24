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


    public function getAvailableQuantityForPeriod($startDate, $endDate, $excludeRequestId = null)
    {
        $query = $this->requestItems()
            ->whereHas('request', function($query) use ($startDate, $endDate) {
                $query->whereHas('status', function($q) {
                    $q->whereIn('name', ['approved', 'delivered']);
                })
                // Controllo sovrapposizione periodi
                ->where(function($dateQuery) use ($startDate, $endDate) {
                    $dateQuery->where(function($q) use ($startDate, $endDate) {
                        // Caso 1: La richiesta inizia prima e finisce durante il periodo
                        $q->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $startDate);
                    })->orWhere(function($q) use ($startDate, $endDate) {
                        // Caso 2: La richiesta inizia durante il periodo
                        $q->where('start_date', '>=', $startDate)
                        ->where('start_date', '<=', $endDate);
                    })->orWhere(function($q) use ($startDate, $endDate) {
                        // Caso 3: La richiesta copre completamente il periodo
                        $q->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                    });
                });
            });

        // Esclude la richiesta corrente se stiamo modificando
        if ($excludeRequestId) {
            $query->where('request_id', '!=', $excludeRequestId);
        }

        $occupiedQuantity = $query->sum('quantity');
        
        return $this->quantity - $occupiedQuantity;
    }











}