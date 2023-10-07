<?php

namespace Tools\ContextVariableSets;

class Showas extends \ContextVariableSets\ContextVariableSet
{
    public $value;
    public $options = [];

    public static $icons = [
        'calendar' => 'calendar',
        'graph' => 'linegraph',
        'list' => 'list',
        'pie' => 'piegraph',
        'spending' => 'dollar',
        'summaries' => 'sigma',
    ];

    public function __construct(string $prefix, array $default_data = [], ?string $partial = null)
    {
        parent::__construct($prefix, $default_data, $partial);

        $data = $this->getRawData();

        $this->value = @$data['value'];
    }

    public function input_names(): array
    {
        return ['value'];
    }
}
