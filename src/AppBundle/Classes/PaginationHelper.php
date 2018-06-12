<?php

namespace AppBundle\Classes;


use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationHelper
{
    /**
     * @param \Doctrine\ORM\Query $dql DQL Query Object
     * @param int $page Current page
     * @param int $limit The total number per page
     * @return \Doctrine\ORM\Tools\Pagination\Paginator Paginator
     */
    public static function paginate($dql, $page = 1, $limit = 5)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }
}