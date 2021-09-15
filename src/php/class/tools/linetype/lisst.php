<?php
namespace tools\linetype;

class lisst extends \Linetype
{
    public function __construct()
    {
        $this->table = 'list';
        $this->label = 'List';
        $this->fields = [
            'name' => function($records) {
                return $records['/']->name;
            },
            'comment' => function($records) {
                return $records['/']->comment;
            },
        ];
        $this->unfuse_fields = [
            'name'=> function($line, $oldline) {
                return $line->name;
            },
            'comment'=> function($line, $oldline) {
                return $line->comment;
            },
        ];
        $this->children = [
            (object) [
                'label' => 'listitems',
                'linetype' => 'listitem',
                'rel' => 'many',
                'tablelink' => 'listlistitem',
            ],
        ];
    }

    public function validate($line)
    {
        $errors = [];

        return $errors;
    }
}
