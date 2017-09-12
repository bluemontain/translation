<?php

namespace curunoir\translation\Facades;
use Illuminate\Support\Facades\Facade;

class TranslationStatic extends Facade
{
    /**
     * The facade accessor for retrieving translation from the IoC.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'translationstatic';
    }
}