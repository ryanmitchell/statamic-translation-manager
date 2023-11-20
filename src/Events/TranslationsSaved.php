<?php

namespace RyanMitchell\StatamicTranslationManager\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Statamic\Contracts\Git\ProvidesCommitMessage;

class TranslationsSaved implements ProvidesCommitMessage
{
    use Dispatchable, SerializesModels;

    public function __construct(public string $locale, public string $namespace, public array $translations)
    {
    }

    public function commitMessage()
    {
        return __('Translation saved', [], config('statamic.git.locale'));
    }
}
