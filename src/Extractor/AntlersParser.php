<?php

namespace RyanMitchell\StatamicTranslationManager\Extractor;

use Statamic\View\Antlers\Language\Analyzers\NodeTypeAnalyzer;
use Statamic\View\Antlers\Language\Nodes\AntlersNode;
use Statamic\View\Antlers\Language\Parser\DocumentParser;
use Statamic\View\Antlers\Language\Runtime\EnvironmentDetails;

class AntlersParser
{
    private $documentParser;

    private $antlersTranslationTags = [
        'trans',
    ];

    public function __construct()
    {
        $this->documentParser = new DocumentParser();

        if (NodeTypeAnalyzer::$environmentDetails == null) {
            $envDetails = new EnvironmentDetails();

            $envDetails->setTagNames(app()->make('statamic.tags')->keys()->all());
            $envDetails->setModifierNames(app()->make('statamic.modifiers')->keys()->all());

            NodeTypeAnalyzer::$environmentDetails = $envDetails;
        }
    }

    public function parse($file)
    {
        $this->documentParser->parse(file_get_contents($file));

        $result = collect($this->documentParser->getNodes())->where(function ($node) {
            return $node instanceof AntlersNode;
        });

        $keys = [];

        /** @var AntlersNode $node */
        foreach ($result as $node) {
            if (! empty($node->processedInterpolationRegions)) {
                $keys = array_merge($keys, $this->locateInterpolatedTranslationKeys($node));
            }

            if (in_array($node->name->name, $this->antlersTranslationTags)) {
                $keyParam = $this->getParameterByName($node, 'key');

                if ($keyParam == null || $keyParam->isModifierParameter || $keyParam->isVariableReference) {
                    $keys[] = $node->name->methodPart;

                    continue;
                }

                $keys[] = $keyParam->value;
            }
        }

        return $keys;
    }

    private function locateInterpolatedTranslationKeys(AntlersNode $rootNode, $foundKeys = [])
    {
        foreach ($rootNode->processedInterpolationRegions as $interpolationRegion) {
            /** @var AntlersNode $node */
            $node = $interpolationRegion[0];

            if (! empty($node->processedInterpolationRegions)) {
                $foundKeys = array_merge($foundKeys, $this->locateInterpolatedTranslationKeys($node, $foundKeys));
            }

            if (in_array($node->name->name, $this->antlersTranslationTags)) {
                $keyParam = $this->getParameterByName($node, 'key');

                if ($keyParam == null || $keyParam->isModifierParameter || $keyParam->isVariableReference) {
                    $foundKeys[] = $node->name->methodPart;

                    continue;
                }

                $foundKeys[] = $keyParam->value;
            }
        }

        return $foundKeys;
    }

    private function getParameterByName(AntlersNode $node, $paramName)
    {
        if ($node->hasParameters) {
            foreach ($node->parameters as $parameter) {
                if ($parameter->name == $paramName) {
                    return $parameter;
                }
            }
        }

        return null;
    }
}
