<?php
namespace contextvariableset;

class Hidden extends \ContextVariableSet
{
    public $value;

    public function __construct(string $prefix, array $default_data = [])
    {
        parent::__construct($prefix, $default_data);

        $data = $this->getRawData();

        $this->value = @$data['value'];
    }
    public function display()
    {
    }

    public function inputs()
    {
        ?>
        <div style="display: none">
            <input class="cv" type="hidden" name="<?= $this->prefix ?>__value" value="<?= $this->value ?>">
        </div>
        <?php
    }
}
