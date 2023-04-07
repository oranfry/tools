<?php
namespace contextvariableset;

class Filter extends \ContextVariableSet
{
    public $field;
    public $cmp;
    public $value;

    public function __construct(string $prefix, array $default_data = [])
    {
        parent::__construct($prefix, $default_data);

        $data = $this->getRawData();

        $this->field = @$data['field'];
        $this->cmp = @$data['cmp'];
        $this->value = @$data['value'];
    }

    public function display()
    {
    }

    public function inputs()
    {
        ?>
        <div style="display: none">
            <input class="cv" plaecholder="field" name="<?= $this->prefix ?>__field" value="<?= $this->field ?>">
            <input class="cv" plaecholder="cmp" name="<?= $this->prefix ?>__cmp" value="<?= $this->cmp ?>">
            <input class="cv" plaecholder="value" name="<?= $this->prefix ?>__value" value="<?= $this->value ?>">
        </div>
    <?php
    }
}
