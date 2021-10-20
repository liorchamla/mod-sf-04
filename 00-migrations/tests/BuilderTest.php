<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../src/UserSearch.php';

class BuilderTest extends TestCase
{
    public function test_it_gives_us_the_correct_sql_query()
    {
        $us = new UserSearch();

        $sql = $us->search();

        $this->assertEquals("SELECT u.name, u.city FROM user AS u", $sql);
    }

    public function test_it_can_add_user_categories()
    {
        $us = new UserSearch;

        $sql = $us->search(true);

        $this->assertEquals(
            "SELECT u.name, u.city, c.title FROM user AS u JOIN categories AS c ON c.id = u.category_id",
            $sql
        );
    }

    public function test_it_can_add_user_articles()
    {
        $us = new UserSearch;

        $sql = $us->search(false, true);

        $this->assertEquals(
            "SELECT u.name, u.city, a.count FROM user AS u JOIN articles AS a ON a.user_id = u.id",
            $sql
        );
    }

    public function test_it_can_add_user_category_and_articles()
    {
        $us = new UserSearch;

        $sql = $us->search(true, true);

        $this->assertEquals(
            "SELECT u.name, u.city, c.title, a.count FROM user AS u JOIN categories AS c ON c.id = u.category_id JOIN articles AS a ON a.user_id = u.id",
            $sql
        );
    }
}
