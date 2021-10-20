<?php

use App\Structure;

class M2
{
    public function export()
    {
        $structure = new Structure;

        $structure->alter('user')
            ->int('age');

        return $structure->getSql();
    }
}
