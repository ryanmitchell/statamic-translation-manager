<?php

namespace RyanMitchell\StatamicTranslationManager\Facades;

use Illuminate\Support\Facades\Facade;

class TranslationManager extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'translation-manager';
    }
}