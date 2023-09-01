<?php

namespace RyanMitchell\StatamicTranslationManager;

use RyanMitchell\StatamicTranslationManager\Events\TranslationSaved;
use RyanMitchell\StatamicTranslationManager\TranslationsManager;
use Illuminate\Http\Request;
use Statamic\Facades\Blueprint as BlueprintAPI;
use Statamic\Facades\Path;
use Statamic\Facades\YAML;
use Statamic\Fields\Blueprint;
use Statamic\Http\Controllers\Controller;

class TranslationController extends Controller
{
    public function edit(Request $request, TranslationsManager $manager)
    {
        $blueprint = $this->buildBlueprint();
        
        dd($manager->getLocales());

        $fields = $blueprint
            ->fields()
            ->addValues([])
            ->preProcess();

        return view('statamic-translation-manager::edit', [
            'blueprint' => $blueprint->toPublishArray(),
            'meta' => $fields->meta(),
            'route' => cp_route('statamic.translation_manager.update'),
            'title' => __('Translation Manager'),
            'values' => $fields->values(),
        ]);
    }

    public function update(Request $request)
    {
        $slug = $request->segment(2);

        $addon = Forma::findBySlug($slug);

        $blueprint = $this->getBlueprint($addon);

        // Get a Fields object, and populate it with the submitted values.
        $fields = $blueprint->fields()->addValues($request->all());

        // Perform validation. Like Laravel's standard validation, if it fails,
        // a 422 response will be sent back with all the validation errors.
        $fields->validate();

        $data = $this->postProcess($fields->process()->values()->toArray());

        $write = ConfigWriter::writeMany($slug, $data);

        TranslationSaved::dispatch($data, $addon);
    }

    private function buildBlueprint(): Blueprint
    {
        $path = Path::assemble('./', 'resources', 'blueprints', 'user.yaml');

        $yaml = YAML::file($path)->parse();

        if ($yaml['tabs'] ?? false) {
            return BlueprintAPI::make()->setContents($yaml);
        }

        return BlueprintAPI::makeFromFields($yaml);
    }

    protected function postProcess(array $values): array
    {
        return $values;
    }

    protected function preProcess(string $handle): array
    {
        return config($handle);
    }
}
