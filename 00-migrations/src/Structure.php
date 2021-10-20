<?php

namespace App;

class Structure
{
    private string $tableName = "";
    private array $fields = [];
    private array $changes = [];
    private string $procedure = 'CREATE';

    public function create(string $tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function alter(string $tableName)
    {
        $this->procedure = 'ALTER';
        $this->tableName = $tableName;
        return $this;
    }

    public function drop(string $tableName)
    {
        $this->procedure = 'DROP';
        $this->tableName = $tableName;
        return $this;
    }

    public function rename(string $oldName, string $newName, string $type)
    {
        $this->changes[] = "CHANGE $oldName $newName $type NOT NULL";
        return $this;
    }

    public function int(string $fieldName)
    {
        $this->add($fieldName, 'int');
        return $this;
    }

    public function string(string $fieldName)
    {
        $this->add($fieldName, 'varchar(255)');
        return $this;
    }

    public function add(string $fieldName, string $type)
    {
        // 'name' 'varchar(255)' => name varchar(255) NOT NULL
        $this->fields[] = "$fieldName $type NOT NULL";
        return $this;
    }

    public function id()
    {
        $this->fields[] = "id INT PRIMARY KEY AUTO_INCREMENT";
        return $this;
    }

    public function getSql(): string
    {
        if ($this->procedure === "DROP") {
            return "DROP TABLE $this->tableName";
        }

        // Générer la requête SQL
        if ($this->procedure === "CREATE") {
            $fields = implode(", ", $this->fields);

            return "$this->procedure TABLE $this->tableName ($fields)";
        }

        // 1. Il faudrait avoir les mêmes phrases que $this->fields
        // mais avec le mot clé ADD devant
        $fields = [];

        foreach ($this->fields as $phrase) {
            $fields[] = "ADD $phrase"; // ADD city varchar(255) NOT NULL
        }

        // 2. Il faudrait aussi imploser avec des ","
        $parts = [...$this->changes, ...$fields];
        $parts = implode(", ", $parts);

        return "$this->procedure TABLE $this->tableName $parts";
    }
}
