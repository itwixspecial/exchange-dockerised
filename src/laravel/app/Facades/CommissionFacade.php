<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CommissionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Services\CommissionService::class;
    }
}
