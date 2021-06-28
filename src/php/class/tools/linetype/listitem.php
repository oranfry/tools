<?php
namespace tools\linetype;

class listitem extends \Linetype
{
    public function __construct()
    {
        $this->table = 'listitem';
        $this->label = 'List Item';
        $this->fields = [
            'item' => function($records) {
                return $records['/']->item;
            },
            'comment' => function($records) {
                return $records['/']->comment;
            },
        ];
        $this->unfuse_fields = [
            'item' => function($line, $oldline) {
                return $line->item;
            },
            'comment' => function($line, $oldline) {
                return $line->comment;
            },
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
