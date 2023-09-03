<?php

namespace RyanMitchell\StatamicTranslationManager\Tests\Unit;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use RyanMitchell\StatamicTranslationManager\Facades\TranslationManager;
use RyanMitchell\StatamicTranslationManager\Tests\TestCase;

class TranslationManagerTest extends TestCase
{
    private $lang = 'statamic';

    public function setUp(): void
    {
        parent::setUp();

        file_put_contents(lang_path("{$this->lang}.php"), '<?php return [];');
    }

    public function tearDown(): void
    {
        parent::tearDown();

        array_map('unlink', array_filter((array) glob(lang_path()."/{$this->lang}/*")));
        unlink(lang_path("{$this->lang}.php"));
    }

    /** @test */
    public function can_save_translations()
    {
        $strings = include(lang_path("{$this->lang}.php"));
        $this->assertNull(Arr::get($strings, 'some_new_key'));

        TranslationManager::saveTranslations($this->lang, [
            '__default' => [
                [
                    'key' => 'some_new_key',
                    'string' => 'Yes',
                ],
            ]
        ]);

        $strings = include(lang_path("{$this->lang}.php"));

        $this->assertSame(Arr::get($strings, 'some_new_key'), 'Yes');
    }

    /** @test */
    public function can_save_mixed_key_translations()
    {
        $strings = include(lang_path("{$this->lang}.php"));
        $this->assertNull(Arr::get($strings, 'some_new_key'));
        $this->assertNull(Arr::get($strings, 'another_key.one'));

        TranslationManager::saveTranslations($this->lang, [
            '__default' => [
                [
                    'key' => 'some_new_key',
                    'string' => 'Yes',
                ],
                [
                    'key' => 'another_key.one',
                    'string' => 'No',
                ],
            ]
        ]);

        $strings = include(lang_path("{$this->lang}.php"));

        $this->assertSame(Arr::get($strings, 'some_new_key'), 'Yes');
        $this->assertSame(Arr::get($strings, 'another_key.one'), 'No');
    }

    /** @test */
    public function can_add_translations()
    {
        $strings = include(lang_path("{$this->lang}.php"));
        $this->assertNull(Arr::get($strings, 'some_new_key'));

        TranslationManager::addTranslations($this->lang, [
            'some_new_key' => 'Yes',
        ]);

        $strings = include(lang_path("{$this->lang}.php"));

        $this->assertSame(Arr::get($strings, 'some_new_key'), 'Yes');
    }

    /** @test */
    public function can_add_mixed_key_translations()
    {
        $strings = include(lang_path("{$this->lang}.php"));
        $this->assertNull(Arr::get($strings, 'some_new_key'));
        $this->assertNull(Arr::get($strings, 'another_key.one'));

        TranslationManager::addTranslations($this->lang, [
            'some_new_key' => 'Yes',
            'another_key.one' => 'No',
        ]);

        $strings = include(lang_path("{$this->lang}.php"));
        $strings2 = include(lang_path("{$this->lang}/another_key.php"));

        $this->assertSame(Arr::get($strings, 'some_new_key'), 'Yes');
        $this->assertSame(Arr::get($strings2, 'one'), 'No');
    }
}
