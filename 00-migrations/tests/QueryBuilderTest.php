<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/QueryBuilder.php';

class QueryBuilderTest extends TestCase
{
    public function test_it_handles_select_and_from()
    {
        $qb = new App\QueryBuilder();

        $qb->select('u.name, u.city');
        $qb->from('user', 'u');

        $sql = $qb->getSql();

        $this->assertEquals("SELECT u.name, u.city FROM user AS u", $sql);
    }

    public function test_it_handles_multiple_selects()
    {
        $qb = new App\QueryBuilder();

        $qb->select('u.name, u.city');
        $qb->from('user', 'u');
        $qb->select('u.age');


        $sql = $qb->getSql();

        $this->assertEquals("SELECT u.name, u.city, u.age FROM user AS u", $sql);
    }

    public function test_it_handles_join()
    {
        $qb = new App\QueryBuilder();

        $qb->select('u.name, u.city');
        $qb->from('user', 'u');

        $qb->select('c.title');
        $qb->join('categories', 'c', 'c.id = u.category_id');
        $qb->join('articles', 'a', 'a.user_id = u.id');

        $sql = $qb->getSql();

        $this->assertEquals("SELECT u.name, u.city, c.title FROM user AS u JOIN categories AS c ON c.id = u.category_id JOIN articles AS a ON a.user_id = u.id", $sql);
    }

    public function test_it_is_fluent()
    {
        $qb = new App\QueryBuilder();

        $sql = $qb->select('u.name, u.city')
            ->from('user', 'u')
            ->select('c.title')
            ->join('categories', 'c', 'c.id = u.category_id')
            ->join('articles', 'a', 'a.user_id = u.id')
            ->getSql();

        $this->assertEquals("SELECT u.name, u.city, c.title FROM user AS u JOIN categories AS c ON c.id = u.category_id JOIN articles AS a ON a.user_id = u.id", $sql);
    }
}
