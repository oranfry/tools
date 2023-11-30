<?php

namespace Tools\ContextVariableSets;

class GroupNavigator extends \ContextVariableSets\ContextVariableSet
{
    public array $value;
    public array $options = [];

    public function __construct(string $prefix, array $default_data = [], ?string $partial = null)
    {
        $jars = $default_data['jars'];
        $report = $default_data['report'];

        unset($default_data['jars']);

        parent::__construct($prefix, $default_data, $partial);

        $data = $this->getRawData();

        $_path = [];

        for ($i = 0; $piece = @$data[$i]; $i++) {
            $_path[] = $piece;
        }

        if (end($_path) !== '') {
            $_path[] = '';
        }

        $path = [];

        $auto_advance_levels = $default_data['auto_advance'] ?? INF;

        while (null !== $piece = array_shift($_path)) {
            $prefix = ($prefix = implode('/', $path)) ? $prefix . '/' : '';
            $groups = $jars->groups($report, $prefix);

            if (
                !$_path
                && count($groups) == 1
                && ($_piece = reset($groups))
                && count($path) < $auto_advance_levels
            ) {
                $_path = [$_piece, ''];

                continue;
            }

            if ($groups) {
                $this->options[] = $groups;
            }

            if (!in_array($piece, $groups)) {
                break;
            }

            $path[] = $piece;
        }

        $this->value = $path;
    }

    public function inputs()
    {
        foreach (array_merge($this->value, [null]) as $i => $selected) {
            ?><input name="<?= $this->prefix ?>__<?= $i ?>" type="hidden" value="<?= $selected ?>"><?php
        }
    }
}
