<?php

namespace contextvariableset;

use subsimple\Period;
use subsimple\ContextVariableSet;

class Daterange extends \ContextVariableSet
{
    public $from;
    public $period;
    public $periods;
    public $rawrawfrom;
    public $rawto;
    public $to;
    public bool $allow_custom;

    public function __construct(string $prefix, array $default_data = [])
    {
        $this->periods = $default_data['periods'] ?? ['day'];
        $this->allow_custom = $default_data['allow_custom'] ?? true;

        unset($default_data['periods'], $default_data['allow_custom']);

        parent::__construct($prefix, $default_data);

        $data = $this->getRawData();

        $this->period = $data['period'] ?? reset($this->periods);

        $current_period = Period::load($this->period);

        if (@$data['rawto']) {
            $this->rawrawfrom = $data['rawrawfrom'];
            $this->rawto = $data['rawto'];
            $this->from = $data['rawrawfrom'];
            $this->to = $data['rawto'];
        } else {
            $this->rawrawfrom = @$data['rawrawfrom'] ?: date('Y-m-d');
            $rawfrom = $current_period->rawstart($this->rawrawfrom);
            $this->from = $current_period->start($rawfrom);

            if ($current_period->step) {
                $this->to = date_shift($current_period->start(date_shift($rawfrom, "+{$current_period->step}")), '-1 day');
            }
        }
    }

    public function display()
    {
        if ($this->period) {
            $current_period = Period::load($this->period);

            if (!@$current_period->suppress_nav) {
                extract($this->computeDates());
            }
        }
        ?>
            <div class="navset">
                <div class="inline-rel">
                    <div class="inline-modal">
                        <div class="nav-dropdown nav-dropdown--always">
                             <?php foreach ($this->periods as $period): ?>
                                <?php $current = (!$this->rawto && $period == $this->period); ?>
                                <a class="<?= $current ? 'current' : '' ?>" href="<?= strtok($_SERVER['REQUEST_URI'], '?') . '?' . $this->constructQuery(['period' => $period, 'rawto' => null]); ?>"><?= Period::load($period)->navlabel ?></a>
                            <?php endforeach ?>
                            <?php if ($this->allow_custom && !@$current_period->suppress_custom): ?>
                                <a class="open-custom-daterange <?= $this->rawto ? 'current' : '' ?>">Custom</a>
                            <?php endif ?>
                            <div class="standard-daterange" style="<?= $this->rawto ? 'display: none' : '' ?>; margin-bottom: 0.5em">
                                <input class="cv-surrogate cv-manip" data-for="<?= $this->prefix ?>__rawrawfrom" type="text" value="<?= $this->from ?>" data-manips="<?= $this->prefix ?>__rawto=" style="width: 6em"><br>
                            </div>
                            <div class="custom-daterange" style="<?= $this->rawto ? '' : 'display: none' ?>">
                                <input class="cv-surrogate cv-manip no-autosubmit" data-for="<?= $this->prefix ?>__rawrawfrom" type="text" value="<?= $this->from ?>" data-manips="<?= $this->prefix ?>__rawto=<?= $this->to ?>" style="width: 6em"><br>
                                to<br>
                                <input class="cv-surrogate no-autosubmit" data-for="<?= $this->prefix ?>__rawto" type="text" value="<?= $this->rawto ?: $this->to ?>" style="width: 6em"><br>
                                <a class="cv-manip" data-manips="">Apply</a>
                            </div>
                        </div>
                    </div>
                    <a class="inline-modal-trigger"><?= strtoupper($this->rawto ? 'c' : $current_period->id) ?></a>
                    <?php if (!@$current_period->suppress_nav): ?>
                        <div class="drnav <?= $highlight[0] ?>"><a class="icon icon--gray icon--arrowleft" href="<?= strtok($_SERVER['REQUEST_URI'], '?') . '?' . $this->constructQuery(['rawrawfrom'=> $prevfrom]); ?>"></a></div>
                        <div class="drnav <?= $highlight[1] ?>"><a class="icon icon--gray icon--dot" href="<?= strtok($_SERVER['REQUEST_URI'], '?') . '?' . $this->constructQuery(['rawrawfrom'=> $currfrom]); ?>"></a></div>
                        <div class="drnav <?= $highlight[2] ?>"><a class="icon icon--gray icon--arrowright" href="<?= strtok($_SERVER['REQUEST_URI'], '?') . '?' . $this->constructQuery(['rawrawfrom'=> $nextfrom]); ?>"></a></div>
                    <?php endif ?>
                </div>
            </div>
        <?php
    }

    public function inputs()
    {
        ?>
        <input class="cv" type="hidden" name="<?= $this->prefix ?>__period" value="<?= $this->period ?>">
        <input class="cv" type="hidden" name="<?= $this->prefix ?>__rawrawfrom" value="<?= $this->rawrawfrom ?>">
        <input class="cv" type="hidden" name="<?= $this->prefix ?>__rawto" value="<?= $this->rawto ?>">
        <?php
    }

    public function getTitle()
    {
        $current_period = Period::load($this->period);

        if (@$current_period->suppress_nav) {
            return $current_period->navlabel;
        }

        extract($this->computeDates());

        if ($inuse) {
            return $current_period->label($this->from, $this->to);
        }

        return date('D j F Y', strtotime($this->from)) . ' ~ ' . date('D j F Y', strtotime($this->to));
    }

    public function computeDates()
    {
        $current_period = Period::load($this->period);

        $rawprevfrom = $current_period->rawstart(date_shift($this->from, "-{$current_period->step}"));
        $rawcurrfrom = $current_period->rawstart(date('Y-m-d'));
        $rawnextfrom = $current_period->rawstart(date_shift($this->from, "+{$current_period->step}"));

        $result = [
            'prevfrom' => $current_period->start($rawprevfrom),
            'currfrom' => $current_period->start($rawcurrfrom),
            'nextfrom' => $current_period->start($rawnextfrom),
            'prevto' => date_shift($current_period->start(date_shift($rawprevfrom, "+{$current_period->step}")), '-1 day'),
            'currto' => date_shift($current_period->start(date_shift($rawcurrfrom, "+{$current_period->step}")), '-1 day'),
            'nextto' => date_shift($current_period->start(date_shift($rawnextfrom, "+{$current_period->step}")), '-1 day'),
            'highlight' => ['', '', ''],
        ];

        $result['inuse'] = ($this->from == date_shift($result['prevto'], '+1 day') && $this->to == date_shift($result['nextfrom'], '-1 day'));

        if ($result['inuse']) {
            $result['highlight'][min(1, max(-1, strcmp($this->from, $result['currfrom']))) + 1] = 'current';
        }

        return $result;
    }
}
