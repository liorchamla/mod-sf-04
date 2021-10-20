<?php

use App\Structure;

class M1
{
    public function export()
    {
        $structure = new Structure;

        $structure->create('user')
            ->string('first_name')
            ->string('last_name')
            ->id();

        return $structure->getSql();
    }
}
