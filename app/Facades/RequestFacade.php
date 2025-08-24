<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ItemFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Models\Request::class;
    }
}