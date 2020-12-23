<?php
namespace tools\linetype;

class listitem extends \Linetype
{
    public function __construct()
    {
        $this->table = 'listitem';
        $this->label = 'List Item';
        $this->fields = [
            (object) [
                'name' => 'item',
                'type' => 'text',
                'fuse' => '{t}.item',
            ],
            (object) [
                'name' => 'comment',
                'type' => 'text',
                'fuse' => '{t}.comment',
            ],
        ];
        $this->unfuse_fields = [
            '{t}.item' => (object) [
                'expression' => ':{t}_item',
                'type' => 'varchar(255)',
            ],
            '{t}.comment' => (object) [
                'expression' => ':{t}_comment',
                'type' => 'varchar(255)',
            ],
        ];
    }

    public function validate($line)
    {
        $errors = [];

        if (!$line->item) {
            $errors[] = 'Empty list item not allowed';
        }

        return $errors;
    }
}
