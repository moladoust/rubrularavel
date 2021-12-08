<?php

namespace Moladoust\Rubrularavel\Facades;

use Illuminate\Support\Facades\Facade;

class Rubrularavel extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'rubrularavel';
    }
}
