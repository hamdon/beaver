<?php
namespace Hamdon\Beaver\Facades;
use Illuminate\Support\Facades\Facade;
class Beaver extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'beaver';
    }
}