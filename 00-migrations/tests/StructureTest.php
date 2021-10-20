<?php

use App\Structure;
use PHPUnit\Framework\TestCase;

class StructureTest extends TestCase
{
    public function test_it_can_create_a_table()
    {
        $structure = new Structure;

        $structure->create('user')
            ->add('name', 'varchar(255)')
            ->add('age', 'int')
            ->id();

        $sql = $structure->getSql();

        $this->assertEquals(
            "CREATE TABLE user (name varchar(255) NOT NULL, age int NOT NULL, id INT PRIMARY KEY AUTO_INCREMENT)",
            $sql
        );
    }

    public function test_it_can_create_a_table_with_shortcuts()
    {
        $structure = new Structure;

        $structure->create('user')
            ->string('name')
            ->int('age')
            ->id();

        $sql = $structure->getSql();

        $this->assertEquals(
            "CREATE TABLE user (name varchar(255) NOT NULL, age int NOT NULL, id INT PRIMARY KEY AUTO_INCREMENT)",
            $sql
        );
    }

    public function test_it_can_alter_a_table()
    {
        $structure = new Structure;

        $structure->alter('user')
            ->rename('name', 'firstName', 'varchar(255)')
            ->string('city')
            ->int('salary');

        $sql = $structure->getSql();

        $this->assertEquals(
            "ALTER TABLE user CHANGE name firstName varchar(255) NOT NULL, ADD city varchar(255) NOT NULL, ADD salary int NOT NULL",
            $sql
        );
    }

    public function test_it_can_drop_table()
    {
        $structure = new Structure;

        $structure->drop('user');

        $sql = $structure->getSql();

        $this->assertEquals(
            "DROP TABLE user",
            $sql
        );
    }
}
