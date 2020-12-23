<?php
namespace tools\linetype;

class lisst extends \Linetype
{
    public function __construct()
    {
        $this->table = 'list';
        $this->label = 'List';
        $this->fields = [
            (object) [
                'name' => 'name',
                'type' => 'text',
                'fuse' => '{t}.name',
            ],
            (object) [
                'name' => 'comment',
                'type' => 'text',
                'fuse' => '{t}.comment',
            ],
        ];
        $this->unfuse_fields = [
            '{t}.name' => (object) [
                'expression' => ':{t}_name',
                'type' => 'varchar(255)',
            ],
            '{t}.comment' => (object) [
                'expression' => ':{t}_comment',
                'type' => 'varchar(255)',
            ],
        ];
        $this->children = [
            (object) [
                'label' => 'listitems',
                'linetype' => 'listitem',
                'rel' => 'many',
                'parent_link' => 'listlistitem',
            ],
        ];
    }

    public function validate($line)
    {
        $errors = [];

        return $errors;
    }
}
