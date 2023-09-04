<?php

namespace RyanMitchell\StatamicTranslationManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use RyanMitchell\StatamicTranslationManager\Facades\TranslationManager;
use RyanMitchell\StatamicTranslationManager\Models;
use Statamic\Facades;
use Statamic\Http\Controllers\Controller;

class BlueprintController extends Controller
{
    private array $commonKeys = ['display', 'instructions', 'placeholder', 'append', 'prepend'];
    private $locales;

    public function index(Request $request)
    {
        $this->locales = Models\Language::all()->pluck('name');

        $missingTranslations = $this->findMissingStringsInBlueprints();

        return view('statamic-translation-manager::scan', [
            'title' => __('Blueprint Scan'),
            'edit_route' => 'translation-manager.blueprints.add',
            'locales' => Models\Language::all(),
            'missing' => $missingTranslations,
        ]);
    }

    public function add(string $locale)
    {
        $this->locales = [$locale];

        $missingTranslations = $this->findMissingStringsInBlueprints()
            ->get($locale)
            ->mapWithKeys(fn ($trans) => [$trans => $trans])
            ->all();

        TranslationManager::addTranslations($locale, $missingTranslations);

        session()->flash('success', "Translations added. Look for strings finishing with [$locale].");

        return redirect(cp_route('translation-manager.edit', ['locale' => $locale]));
    }

    private function findMissingStringsInBlueprints()
    {
        $missingTranslations = collect()->merge(Facades\Collection::all()
            ->map(function ($collection) {
                return $collection->entryBlueprints()
                    ->map(function($blueprint) {
                        return $this->checkBlueprint($blueprint);
                    })
                    ->filter();
            })
            ->flatten(1)
            ->filter()
        )
        ->merge(Facades\Taxonomy::all()
            ->map(function ($taxonomy) {
                return $taxonomy->termBlueprints()
                    ->map(function($blueprint) {
                        return $this->checkBlueprint($blueprint);
                    })
                    ->filter();
            })
            ->flatten(1)
            ->filter()
        )
        ->merge(Facades\Nav::all()
            ->map(function ($nav) {
                return $this->checkBlueprint($nav->blueprint());
            })
            ->filter()
        )
        ->merge(Facades\GlobalSet::all()
            ->map(function ($set) {
                return $this->checkBlueprint($set->blueprint());
            })
            ->filter()
        )
        ->merge(Facades\AssetContainer::all()
            ->map(function ($container) {
                return $this->checkBlueprint($container->blueprint());
            })
            ->filter()
        )
        ->merge(Facades\Form::all()
            ->map(function ($form) {
                return $this->checkBlueprint($form->blueprint());
            })
            ->filter()
        )
        ->merge(Facades\Fieldset::all()
            ->map(function ($fieldset) {
                return $this->findMissingFieldStrings($fieldset->fields()->all()->toArray());
            })
            ->filter()
        )
        ->merge([$this->checkBlueprint(Facades\User::make()->blueprint())])
        ->merge([$this->checkBlueprint(Facades\UserGroup::make()->blueprint())]);

        $missingTranslations = $missingTranslations
            ->flatten(1)
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

    private function checkBlueprint($blueprint): array
    {
        $missing = [];

        foreach ($blueprint->tabs() as $tab) {
            $tab = $tab->contents();

            $string = Arr::get($tab, 'display', false);
            if ($string) {
                $missing = array_merge($missing, $this->checkStringInLocales($string));
            }

            foreach ($tab['sections'] ?? [] as $section) {
                $string = Arr::get($section, 'display', false);
                if ($string) {
                    $missing = array_merge($missing, $this->checkStringInLocales($string));
                }

                $string = Arr::get($section, 'instructions', false);
                if ($string) {
                    $missing = array_merge($missing, $this->checkStringInLocales($string));
                }
            }
        }

        return array_merge($missing, $this->findMissingFieldStrings($blueprint->fields()->items()->all()));
    }

    private function findMissingFieldStrings(array $fields): array
    {
        $missing = [];

        foreach ($fields as $field) {
            if (isset($field['field'])) {
                $field = $field['field'];
            }

            foreach ($this->commonKeys as $key) {
                if ($string = Arr::get($field, $key, false)) {
                    if ($string) {
                        $missing = array_merge($missing, $this->checkStringInLocales($string));
                    }
                }
            }

            // options: select, checkbox, radio, button group
            if ($options = Arr::get($field, 'options', false)) {
                foreach ($options as $string) {
                    $missing = array_merge($missing, $this->checkStringInLocales($string));
                }
            }

            switch (Arr::get($field, 'type', 'none')) {
                case 'bard':
                case 'replicator':
                    $missing = array_merge($missing, $this->findMissingFieldStrings($field['sets'] ?? []));
                break;

                case 'grid':
                    $string = Arr::get($field, 'input_label', false);
                    if ($string) {
                        $missing = array_merge($missing, $this->checkStringInLocales($string));
                    }

                    $missing = array_merge($missing, $this->findMissingFieldStrings($field['fields'] ?? []));
                break;

                case 'revealer':
                    $string = Arr::get($field, 'input_label', false);
                    if ($string) {
                        $missing = array_merge($missing, $this->checkStringInLocales($string));
                    }
                break;

                case 'toggle':
                    $string = Arr::get($field, 'inline_label', false);
                    if ($string) {
                        $missing = array_merge($missing, $this->checkStringInLocales($string));
                    }
                break;
            }

            // TODO: add a pipeline here with a DTO
            // so 'hooks' can be provided to check custom fieldtypes
            // or add additional functionality
        }

        return $missing;
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
        return Lang::has($string."", $locale);
    }
}
