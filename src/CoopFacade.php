<?php

namespace Amunyua\Coop;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Amunyua\CoOp\Skeleton\SkeletonClass
 */
class CoopFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'coop';
    }
}
