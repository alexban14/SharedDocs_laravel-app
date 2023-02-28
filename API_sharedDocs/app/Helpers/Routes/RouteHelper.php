<?php

namespace App\Helpers\Routes;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class RouteHelper
{
    public static function includeRouteFiles($folder)
    {
        // iterate thru the v1 folder recursively so it automatically picks up a new file with api endpoints
        $dirIterator = new \RecursiveDirectoryIterator($folder);

        /** @var \RecursiveDirectoryIterator | \RecursiveIteratorIterator $it */
        $it = new \RecursiveIteratorIterator($dirIterator);

        while($it->valid())
        {
            if(
                !$it->isDot()
                && $it->isFile()
                && $it->isReadable()
                && $it->current()->getExtension() === 'php'
            ) {
                require $it->key();
            }
            $it->next();
        }
    }
}
