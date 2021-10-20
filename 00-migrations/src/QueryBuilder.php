<?php

namespace App;

class QueryBuilder
{
    private string $selects = "";
    private string $from = "";
    private string $join = "";

    public function join(string $table, string $alias, string $join)
    {
        $this->join .= " JOIN $table AS $alias ON $join";

        return $this;
    }

    public function select(string $select)
    {
        if ($this->selects) {
            $this->selects .= ", $select";
            return $this;
        }

        $this->selects .= $select;

        return $this;
    }

    public function from(string $table, string $alias)
    {
        $this->from = "$table AS $alias";

        return $this;
    }

    public function getSql(): string
    {
        return "SELECT $this->selects FROM $this->from" . ($this->join ?  $this->join : "");
    }
}
