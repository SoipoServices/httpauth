<?php

namespace SoipoServices\HttpAuth;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Soiposervices\Httpauth\Skeleton\SkeletonClass
 */
class HttpAuthFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'httpauth';
    }
}
