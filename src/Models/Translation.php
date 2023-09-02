<?php

namespace RyanMitchell\StatamicTranslationManager\Models;

use Illuminate\Database\Eloquent\Model;
use RyanMitchell\StatamicTranslationManager\Facades;
use Sushi\Sushi;

class Translation extends Model
{
    use Sushi;
    
    public $sushiInsertChunkSize = 25;
    
    protected $schema = [
        'file' => 'string',
        'locale' => 'string',
        'key' => 'string',
        'string' => 'string'
    ];

    public function getRows()
    {
        return Facades\TranslationManager::getTranslations();    
    }
    
    protected function sushiShouldCache()
    {
        return false;
    }
}