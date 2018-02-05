<?php

/*
 * This file is part of the BenGorFile package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGorFile\DoctrineORMBridge\Infrastructure\Persistence;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DoctrineORMListOfIdsSpecification implements DoctrineORMQuerySpecification
{
    private $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    public function buildQuery(QueryBuilder $queryBuilder)
    {
        return $queryBuilder
            ->select('f')
            ->where($queryBuilder->expr()->in('f.id', ':ids'))
            ->setParameter('ids', $this->ids)
            ->getQuery();
    }
}
