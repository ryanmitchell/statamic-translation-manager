<?php

namespace RyanMitchell\StatamicTranslationManager\Tests\Unit;

use Illuminate\Support\Facades\Lang;
use RyanMitchell\StatamicTranslationManager\Facades\TranslationManager;
use RyanMitchell\StatamicTranslationManager\Tests\TestCase;

class TranslationManagerTest extends TestCase
{
    /** @test */
    public function can_save_translations()
    {
        $this->assertFalse(Lang::has('some_new_key', '__random__'));

        TranslationManager::saveTranslations('_random_', [
            '__default' => [
                [
                    'key' => 'some_new_key',
                    'string' => 'Yes',
                ],
            ]
        ]);

        $this->assertTrue(Lang::has('some_new_key', '__random__'));
    }

    /** @test */
    public function can_save_mixed_key_translations()
    {
        return;
    }

    /** @test */
    public function can_add_translations()
    {
        return;
    }

    /** @test */
    public function can_add_mixed_key_translations()
    {
        return;
    }
}
