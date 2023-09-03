<?php

namespace RyanMitchell\StatamicTranslationManager\Extractor;

use KKomelin\TranslatableStringExporter\Core\FileFinder;
use Statamic\View\Antlers\Engine;

class AntlersFileFinder extends FileFinder
{
    public function __construct()
    {
        parent::__construct();
        $this->patterns = [];

        foreach (Engine::EXTENSIONS as $extension) {
            $this->patterns[] = '*.'.$extension;
        }
    }
}
