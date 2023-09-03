<?php

namespace RyanMitchell\StatamicTranslationManager\Models;

use Illuminate\Database\Eloquent\Model;
use RyanMitchell\StatamicTranslationManager\Facades;
use Statamic\Facades\Blink;
use Sushi\Sushi;

class Language extends Model
{
    use Sushi;

    protected $schema = [
        'name' => 'string',
    ];

    public function getRows()
    {
        return Blink::once('translation.locales', function () {
            return Facades\TranslationManager::getLocales();
        });
    }

    protected function sushiShouldCache()
    {
        return false;
    }
}
