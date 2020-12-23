<?php
namespace tools\blend;

class lists extends \Blend
{
    public function __construct()
    {
        $this->label = 'Lists';
        $this->linetypes = ['list',];
        $this->fields = [
            (object) [
                'name' => 'name',
                'type' => 'text',
            ],
            (object) [
                'name' => 'comment',
                'type' => 'text',
            ],
        ];
    }
}
