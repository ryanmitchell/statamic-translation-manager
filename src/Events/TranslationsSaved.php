<?php

namespace RyanMitchell\StatamicTranslationManager\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TranslationsSaved
{
    use Dispatchable, SerializesModels;

    public function __construct(public string $locale, public string $namespace, public array $translations)
    {
    }
}
