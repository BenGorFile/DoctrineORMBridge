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
use LIN3S\SharedKernel\Infrastructure\Persistence\Doctrine\ORM\DoctrineCountSpecification;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DoctrineORMFilterByNameSpecification implements DoctrineORMQuerySpecification, DoctrineCountSpecification
{
    private $name;
    private $offset;
    private $limit;

    public function __construct($aName, $offset, $limit)
    {
        $this->name = $aName;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    public function buildQuery(QueryBuilder $queryBuilder)
    {
        if ($this->limit > 0) {
            $queryBuilder->setMaxResults($this->limit);
        }

        if (!empty($this->name)) {
            $queryBuilder
                ->where($queryBuilder->expr()->like('f.name.name', ':name'))
                ->setParameter('name', '%' . $this->name . '%');
        }

        return $queryBuilder
            ->select('f')
            ->setFirstResult($this->offset)
            ->orderBy('f.updatedOn', 'DESC')
            ->getQuery();
    }

    public function buildCount(QueryBuilder $queryBuilder)
    {
        if (!empty($this->name)) {
            $queryBuilder
                ->where($queryBuilder->expr()->like('f.name.name', ':name'))
                ->setParameter('name', '%' . $this->name . '%');
        }

        return $queryBuilder
            ->select($queryBuilder->expr()->count('f.id'))
            ->where($queryBuilder->expr()->like('f.name.name', ':name'))
            ->setParameter('name', '%' . $this->name . '%')
            ->getQuery();
    }
}
