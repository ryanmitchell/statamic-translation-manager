<?php

namespace RyanMitchell\StatamicTranslationManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use KKomelin\TranslatableStringExporter\Core\StringExtractor;
use RyanMitchell\StatamicTranslationManager\Extractor\TranslationStringExtractor;
use RyanMitchell\StatamicTranslationManager\Facades\TranslationManager;
use RyanMitchell\StatamicTranslationManager\Models;
use Statamic\Facades;
use Statamic\Http\Controllers\Controller;

class TemplateController extends Controller
{
    private $locales;

    public function index(Request $request)
    {
        $this->locales = Models\Language::all()->pluck('name');

        $missingTranslations = $this->findMissingStringsInTemplates();

        return view('statamic-translation-manager::scan', [
            'title' => __('Template Scan'),
            'edit_route' => 'translation-manager.templates.add',
            'locales' => Models\Language::all(),
            'missing' => $missingTranslations,
        ]);
    }

    public function add(string $locale)
    {
        $this->locales = [$locale];

        $missingTranslations = $this->findMissingStringsInTemplates()
            ->get($locale)
            ->mapWithKeys(fn ($trans) => [$trans => $trans." [$locale]"])
            ->all();

        TranslationManager::addTranslations($locale, $missingTranslations);

        session()->flash('success', "Translations added. Look for strings finishing with [$locale].");

        return redirect(cp_route('translation-manager.edit', ['locale' => $locale]));
    }

    private function findMissingStringsInTemplates()
    {
        $bladeStrings = (new StringExtractor())->extract();
        $antlersStrings = (new TranslationStringExtractor())->extract();

        $translationStrings = array_merge($bladeStrings, $antlersStrings);

        $missingTranslations = [];
        foreach ($translationStrings as $string) {
            $missingTranslations = array_merge($missingTranslations, $this->checkStringInLocales($string));
        }

        $missingTranslations = collect($missingTranslations)
            ->filter()
            ->unique()
            ->sortBy('string')
            ->sortBy('locale')
            ->groupBy('locale')
            ->map(function ($locale) {
                return $locale->map(fn ($string) => $string['string']);
            });

        return $missingTranslations;
    }

    private function checkStringInLocales(string $string): array
    {
        $missing = [];

        foreach ($this->locales as $locale) {
            if (! $this->hasTranslation($string, $locale)) {
                $missing[] = [
                    'locale' => $locale,
                    'string' => $string,
                ];
            }
        }

        return $missing;
    }

    private function hasTranslation($string, $locale): bool
    {
        return $string !== __($string, [], $locale);
    }
}
