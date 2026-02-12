<?php

namespace RyanMitchell\StatamicTranslationManager\Tests\Unit\Extractor;

use PHPUnit\Framework\Attributes\Test;
use RyanMitchell\StatamicTranslationManager\Tests\TestCase;
use RyanMitchell\StatamicTranslationManager\Extractor\AntlersParser;

class AntlersParserTest extends TestCase
{
    #[Test]
    public function it_can_parse_trans_modifier_from_antlers_file()
    {
        $parser = new AntlersParser;

        $keys = $parser->parse($this->getSampleFilePath('trans_modifier.antlers.html'));

        $this->assertEquals([
            'Translation using modifier'
        ], $keys);
    }

    #[Test]
    public function it_can_parse_trans_tag_from_antlers_file()
    {
        $parser = new AntlersParser;

        $keys = $parser->parse($this->getSampleFilePath('trans_tag.antlers.html'));

        $this->assertEquals([
            'Translation using tag'
        ], $keys);
    }
}
