<?php

namespace contextvariableset;

use \Config;
use \Period;

class Repeater extends \ContextVariableSet
{
    public $period;
    public $n;
    public $pegdate;
    public $day;
    public $month;
    public $round;
    public $offset;
    public $ff;

    public function __construct(string $prefix, array $default_data = [])
    {
        parent::__construct($prefix, $default_data);

        $data = $this->getRawData();

        if (@$data['period']) {
            $this->period = @$data['period'];
            $this->n = @$data['n'] ?: 7;
            $this->pegdate = @$data['pegdate'] ?: date('Y-m-d');
            $this->day = @$data['day'];
            $this->month = @$data['month'];
            $this->round = @$data['round'];
            $this->offset = @$data['offset'];
            $this->ff = @$data['ff'];
        }
    }

    public function display()
    {
        ?>
        <div class="inline-rel">
            <div class="inline-modal repeater-modal">
                <div class="nav-dropdown--spacey" style="white-space: nowrap; width: 17em;">
                    <div class="form-row">
                        <div class="form-row__label">Repeater</div>
                        <div class="form-row__value">
                            <select class="repeater-select cv-surrogate no-autosubmit" data-for="<?= $this->prefix ?>__period">
                                <option></option>
                                <?php foreach (['day', 'month', 'year'] as $period): ?>
                                    <option <?= ($period == $this->period) ? 'selected="selected"' : '' ?> value="<?= $period ?>"><?= $period ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div style="clear: both"></div>
                    </div>

                    <div class="form-row" data-repeaters="day">
                        <div class="form-row__label">n</div>
                        <div class="form-row__value">
                            <input class="cv-surrogate no-autosubmit" data-for="<?= $this->prefix ?>__n" type="number" step="1" min="1" value="<?= $this->n ?>" style="width: 4em">
                        </div>
                        <div style="clear: both"></div>
                    </div>

                    <div class="form-row" data-repeaters="day">
                        <div class="form-row__label">Peg Date</div>
                        <div class="form-row__value">
                            <input class="cv-surrogate no-autosubmit" data-for="<?= $this->prefix ?>__pegdate" type="text" value="<?= $this->pegdate ?>" style="width: 7em"><span class="button fromtoday">&bull;</span>
                        </div>
                        <div style="clear: both"></div>
                    </div>

                    <div class="form-row" data-repeaters="month year">
                        <div class="form-row__label">Day</div>
                        <div class="form-row__value">
                            <input class="cv-surrogate no-autosubmit" data-for="<?= $this->prefix ?>__day" type="text" value="<?= $this->day ?>" style="width: 7em">
                        </div>
                        <div style="clear: both"></div>
                    </div>

                    <div class="form-row" data-repeaters="year">
                        <div class="form-row__label">Month</div>
                        <div class="form-row__value">
                            <input class="cv-surrogate no-autosubmit" data-for="<?= $this->prefix ?>__month" type="text" value="<?= $this->month ?>" style="width: 7em">
                        </div>
                        <div style="clear: both"></div>
                    </div>

                    <div class="form-row" data-repeaters="day month year">
                        <div class="form-row__label">F/F</div>
                        <div class="form-row__value">
                             <select class="cv-surrogate no-autosubmit" data-for="<?= $this->prefix ?>__ff">
                                <option></option>
                                <?php foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $i => $ff): ?>
                                    <option <?= ($i + 1 == $this->ff) ? 'selected="selected"' : '' ?> value="<?= $i + 1?>"><?= $ff ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div style="clear: both"></div>
                    </div>

                    <div class="form-row" data-repeaters="month year">
                        <div class="form-row__label">Offset</div>
                        <div class="form-row__value">
                            <input class="cv-surrogate no-autosubmit" data-for="<?= $this->prefix ?>__offset" type="text" value="<?= $this->offset ?>" style="width: 7em">
                        </div>
                        <div style="clear: both"></div>
                    </div>

                    <div class="form-row" data-repeaters="month year">
                        <div class="form-row__label">Round</div>
                        <div class="form-row__value">
                             <select class="cv-surrogate no-autosubmit" data-for="<?= $this->prefix ?>__round">
                                <option></option>
                                <option <?= $this->round == 'Yes' ? 'selected': '' ?>>Yes</option>
                            </select>
                        </div>
                        <div style="clear: both"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-row__label">&nbsp;</div>
                        <div class="form-row__value">
                            <a class="button cv-manip" data-manips="<?= $this->prefix ?>__period=">Clear</a>
                            <a class="button cv-manip" data-manips="">Apply</a>
                        </div>
                        <div style="clear: both"></div>
                    </div>
                </div>
            </div>
            <div class="inline-modal-trigger drnav <?= $this->period ? 'current' : '' ?>"><i class="icon icon--gray icon--repeat"></i></div>
        </div>
        <?php
    }

    public function inputs()
    {

        ?>
        <div style="display: none">
            <input class="cv" type="hidden" name="<?= $this->prefix ?>__period" value="<?= $this->period ?>">
            <input class="cv" type="hidden" name="<?= $this->prefix ?>__n" value="<?= $this->n ?>">
            <input class="cv" type="hidden" name="<?= $this->prefix ?>__pegdate" value="<?= $this->pegdate ?>">
            <input class="cv" type="hidden" name="<?= $this->prefix ?>__day" value="<?= $this->day ?>">
            <input class="cv" type="hidden" name="<?= $this->prefix ?>__month" value="<?= $this->month ?>">
            <input class="cv" type="hidden" name="<?= $this->prefix ?>__round" value="<?= $this->round ?>">
            <input class="cv" type="hidden" name="<?= $this->prefix ?>__offset" value="<?= $this->offset ?>">
            <input class="cv" type="hidden" name="<?= $this->prefix ?>__ff" value="<?= $this->ff ?>">
        </div>
        <?php
    }

    public function render()
    {
        $r = "{$this->period}:";

        if ($this->period == 'day') {
            $r .= "{$this->pegdate}.{$this->n}";
        } else {
            $r .= "{$this->day}";

            if ($this->period == 'year') {
                $r .= "/{$this->month}";
            }
        }

        if ($this->round !== null) {
            $r .= 'r';
        }

        if ($this->ff) {
            $r .= 'f' . $this->ff;
        }

        if ($this->offset !== null) {
            $r .= str_replace(
                [' ', 'days', 'day', 'weeks', 'week', 'months', 'month', 'years', 'year'],
                ['',  'd',    'd',   'w',     'w',    'm',      'm',      'y',    'y'],
                $this->offset
            );
        }

        return $r;
    }
}
