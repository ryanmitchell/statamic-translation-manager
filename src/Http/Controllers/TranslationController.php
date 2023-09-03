<?php

namespace RyanMitchell\StatamicTranslationManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use RyanMitchell\StatamicTranslationManager\Facades\TranslationManager;
use RyanMitchell\StatamicTranslationManager\Models;
use Statamic\Facades;
use Statamic\Fields\Blueprint;
use Statamic\Http\Controllers\Controller;

class TranslationController extends Controller
{
    public function index(Request $request)
    {
        return view('statamic-translation-manager::index', [
            'title' => __('Translation Manager'),
            'locales' => Models\Language::all(),
        ]);
    }

    public function edit(Request $request, string $locale)
    {
        [$namespaces, $translations] = $this->getDataForLocale($locale);

        $blueprint = $this->buildBlueprint($namespaces->toArray());

        $fields = $blueprint
            ->fields()
            ->addValues($translations->toArray())
            ->preProcess();

        return view('statamic-translation-manager::edit', [
            'blueprint' => $blueprint->toPublishArray(),
            'meta' => $fields->meta(),
            'route' => cp_route('translation-manager.update', $locale),
            'title' => __('Manage Translation: :locale', ['locale' => $locale]),
            'values' => $fields->values(),
        ]);
    }

    public function update(Request $request, string $locale)
    {
        [$namespaces, $translations] = $this->getDataForLocale($locale);

        $blueprint = $this->buildBlueprint($namespaces->toArray());

        $fields = $blueprint->fields()->addValues($request->all());

        $fields->validate();

        $translations = $fields->process()->values()->toArray();

        TranslationManager::saveTranslations($locale, $translations);
    }

    private function buildBlueprint(array $namespaces): Blueprint
    {
        $path = Facades\Path::assemble(__DIR__.'/../../../', 'resources', 'blueprints', 'manager.yaml');

        $yaml = Facades\YAML::file($path)->parse();

        $tab = $yaml['tabs']['main'];
        unset($yaml['tabs']['main']);

        foreach ($namespaces as $namespace) {
            $newTab = $tab;
            Arr::set($newTab, 'display', $namespace == '__default' ? __('Root') : ucfirst($namespace));
            Arr::set($newTab, 'sections.0.fields.0.handle', $namespace);
            $yaml['tabs']['tab_'.$namespace] = $newTab;
        }

        return Facades\Blueprint::make()->setContents($yaml);
    }

    private function getDataForLocale($locale): array
    {
        $translations = Models\Translation::where('locale', $locale)
            ->orderBy('key')
            ->get()
            ->groupBy('file');

        $namespaces = $translations->keys()->sort();

        return [$namespaces, $translations];
    }
}
