<?php

namespace RyanMitchell\StatamicTranslationManager\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TranslationSaved
{
    use Dispatchable, SerializesModels;

    public function __construct(public string $key, public array $data)
    {
    }
}
