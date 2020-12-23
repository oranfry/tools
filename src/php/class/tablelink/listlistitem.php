<?php
namespace tablelink;

class listlistitem extends \Tablelink
{
    public function __construct()
    {
        $this->tables = ['list', 'listitem'];
        $this->middle_table = 'tablelink_list_listitem';
        $this->ids = ['list', 'listitem'];
        $this->type = 'onemany';
    }
}
