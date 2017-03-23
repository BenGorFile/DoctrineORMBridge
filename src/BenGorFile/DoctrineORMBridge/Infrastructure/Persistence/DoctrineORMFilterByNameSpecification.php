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

use BenGorFile\File\Domain\Model\FileName;
use Doctrine\ORM\QueryBuilder;
use LIN3S\SharedKernel\Infrastructure\Persistence\Doctrine\ORM\DoctrineCountSpecification;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DoctrineORMFilterByNameSpecification implements DoctrineORMQuerySpecification, DoctrineCountSpecification
{
    private $name;

    public function __construct(FileName $aName)
    {
        $this->name = $aName;
    }

    public function buildQuery(QueryBuilder $queryBuilder)
    {
        return $queryBuilder
            ->select('f')
            ->where($queryBuilder->expr()->like('f.name.name', ':name'))
            ->setParameter('name', '%' . $this->name->name() . '%')
            ->getQuery();
    }

    public function buildCount(QueryBuilder $queryBuilder)
    {
        return $queryBuilder
            ->select($queryBuilder->expr()->count('f.id'))
            ->where($queryBuilder->expr()->eq('f.name.name', ':name'))
            ->setParameter('name', '%' . $this->name->name() . '%')
            ->getQuery();
    }
}
