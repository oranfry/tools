<?php

namespace OranFry\Tools\ContextVariableSets;

use OranFry\Obex\Obex;

class ChildNavigator extends \OranFry\ContextVariableSets\ContextVariableSet
{
    public array $value = [];
    public array $options = [];
    public array $info;
    public ?object $context = null;
    public ?object $me = null;

    public function __construct(string $prefix, array $default_data = [], ?string $partial = null)
    {
        parent::__construct($prefix, $default_data, $partial);

        $this->info = $this->parseChildPath(
            $default_data['jars'],
            $default_data['report'],
            $default_data['linetype_name'],
            $default_data['line_id'],
            $default_data['lines'],
            $default_data['linetypes'],
            $this->me,
            $this->context,
        );

        unset(
            $default_data['jars'],
            $default_data['report'],
            $default_data['linetype_name'],
            $default_data['line_id'],
            $default_data['lines'],
            $default_data['linetypes'],
        );

        foreach ($this->info as $infum) {
            $this->value[] = (object) [
                'property' => $infum->property,
                'id' => $infum->id,
            ];

            $this->options[] = (object) [
                'property' => $infum->property_options,
                'id' => $infum->id_options,
            ];
        }
    }

    public function inputs()
    {
        foreach ($this->value as $i => $piece) {
            echo 'window.contextVariableSets.' . $this->prefix . '__property_' . $i . " = '" . htmlspecialchars($piece->property) . "';";
    
            if ($piece->id) {
                echo 'window.contextVariableSets.' . $this->prefix . '__id_' . $i . " = '" . htmlspecialchars($piece->id) . "';";
            }
        }
    }

    public function parseChildPath(
        object $jars,
        string $report_name,
        ?string $linetype,
        ?string $id,
        array &$lines,
        array &$linetypes,
        &$me = null,
        &$context = null
    ): array
    {
        if ($id === null) {
            return [];
        }

        $childpath = [];

        $report = Obex::from($jars->reports())
            ->find('name', 'is', $report_name);;

        $me = Obex::from($lines)
            ->filter('id', 'is', $id)
            ->find('type', 'is', $linetype);

        if (!$me) {
            return [];
        }

        $myLinetype = $report->linetypes[$linetype];

        for ($i = 0; $property = @$_GET[$this->prefix . '__property_' . $i]; $i++) {
            if (!preg_match('/^([a-z]+)$/', $property)) {
                throw new \Exception('Invalid childpath property');
            }

            $context = $me;
            $id = @$_GET[$this->prefix . '__id_' . $i];

            if ($id && !preg_match('/^([0-9a-f]{64})$/', $id)) {
                throw new \Exception('Invalid childpath id');
            }

            $property_options = array_keys($myLinetype->children);
            $child = $myLinetype->children[$property];

            $lines = $me->$property;
            $linetype = $child->linetype;
            $only_parent = $child->only_parent;

            $id_options = $id ? [substr($id, 0, 6) => $id] : [];

            $linetypes = Obex::from($jars->linetypes())
                ->filter('name', 'is', $linetype)
                ->resolve();

            if ($id) {
                $me = Obex::from($lines)
                    ->filter('id', 'is', $id)
                    ->find('type', 'is', $linetype);
            }

            $myLinetype = $child;

            $childpath[] = (object) compact(
                'id',
                'linetype',
                'only_parent',
                'property',
                'property_options',
                'id_options',
            );

            if (!$me) {
                break;
            }
        }

        return $childpath;
    }
}
