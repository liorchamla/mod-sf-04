<?php

// require_once __DIR__ . '/QueryBuilder.php';

class UserSearch
{
    public function search(bool $withCategory = false, bool $withArticle = false)
    {
        $qb = new App\QueryBuilder; // FQCN : Fully Qualified Class Name

        $qb->select('u.name, u.city')
            ->from('user', 'u');

        if ($withCategory) {
            $qb->select('c.title')
                ->join("categories", "c", "c.id = u.category_id");
        }

        if ($withArticle) {
            $qb->select('a.count')
                ->join('articles', 'a', 'a.user_id = u.id');
        }

        return $qb->getSql();

        // $baseSelect = "SELECT u.name, u.city";
        // $baseFrom = "FROM user AS u";

        // if ($withCategory) {
        //     $baseSelect .= ", c.title";
        //     $baseFrom .= " JOIN categories AS c ON c.id = u.category_id";
        // }

        // if ($withArticle) {
        //     $baseSelect .= ", a.count";
        //     $baseFrom .= " JOIN articles AS a ON a.user_id = u.id";
        // }

        // return $baseSelect . ' ' . $baseFrom;
    }
}
