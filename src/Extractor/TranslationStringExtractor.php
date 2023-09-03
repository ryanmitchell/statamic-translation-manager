<?php

namespace RyanMitchell\StatamicTranslationManager\Extractor;

use KKomelin\TranslatableStringExporter\Core\StringExtractor;

class TranslationStringExtractor extends StringExtractor
{
    /**
     * @var AntlersFileFinder
     */
    private $finder;

    /**
     * @var AntlersParser
     */
    private $parser;

    public function __construct()
    {
        $this->finder = new AntlersFileFinder();
        $this->parser = new AntlersParser();
    }

    public function extract()
    {
        $strings = [];

        $files = $this->finder->find();
        foreach ($files as $file) {
            $strings = array_merge($strings, $this->parser->parse($file));
        }

        return $this->formatArray($strings);
    }
}
