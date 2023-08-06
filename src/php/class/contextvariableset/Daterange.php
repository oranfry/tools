<?php

namespace contextvariableset;

class Daterange extends \ContextVariableSet
{
    public $chunk = null;
    public $date;
    public $period = null;
    public $period_id = null;
    public $periods;

    public function __construct(string $prefix, array $default_data = [])
    {
        $this->periods = array_filter($default_data['periods'] ?? []);

        unset($default_data['periods']);

        parent::__construct($prefix, $default_data);

        $data = $this->getRawData();

        $this->date = @$data['date'] ?: date('Y-m-d');

        $period_ids = array_keys($this->periods);
        $period_id = @$data['period'] ?: @reset($period_ids);

        if ($period_id && $period = @$this->periods[$period_id]) {
            $this->period_id = $period_id;
            $this->period = $period;
            $this->chunk = $this->period->chunk($this->date);
        }
    }

    public function display()
    {
        ss_require('src/php/partial/contextvariableset/daterange/display.php', array_merge(
            ['daterange' => $this],
            $this->computeDates(),
        ), $this);
    }

    public function inputs()
    {
        ss_require('src/php/partial/contextvariableset/daterange/inputs.php', ['daterange' => $this], $this);
    }

    public function getTitle()
    {
        return $this->chunk->label();
    }

    public function computeDates()
    {
        $curr = $this->period->chunk(date('Y-m-d'));
        $next = $this->chunk->next();
        $prev = $this->chunk->prev();

        $highlight = ['', '', ''];
        $highlight[($this->chunk->start() <=> $curr->start()) + 1] = 'current';

        return compact(
            'highlight',
            'next',
            'prev',
        );
    }
}

