<?php

namespace RyanMitchell\StatamicTranslationManager\Events;

use Illuminate\Queue\SerializesModels;
use Statamic\Contracts\Git\ProvidesCommitMessage;
use Statamic\Events\Event;

class TranslationsSaved extends Event implements ProvidesCommitMessage
{
    use SerializesModels;

    public function __construct(public string $locale, public string $namespace, public array $translations)
    {
    }

    public function commitMessage()
    {
        return __('Translation saved', [], config('statamic.git.locale'));
    }
}
