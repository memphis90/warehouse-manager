<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Facades\ItemFacade;

class RequestFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Models\Request::class;
    }

    public static function validateItemAvailability($itemId, $quantity, $startDate, $endDate, $excludeRequestId = null)
    {
        $item = ItemFacade::find($itemId);
        
        if (!$item) {
            return [
                'valid' => false,
                'message' => 'Item not found'
            ];
        }

        $availableQuantity = ItemFacade::getAvailableQuantityForPeriod(
            $startDate, 
            $endDate, 
            $excludeRequestId
        );

        if ($quantity > $availableQuantity) {
            return [
                'valid' => false,
                'message' => "Only {$availableQuantity} units available for the selected period",
                'available_quantity' => $availableQuantity
            ];
        }

        return [
            'valid' => true,
            'available_quantity' => $availableQuantity
        ];
    }

}