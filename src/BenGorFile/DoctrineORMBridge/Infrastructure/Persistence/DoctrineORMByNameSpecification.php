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

use BenGorFile\File\Domain\Model\File;
use BenGorFile\File\Domain\Model\FileName;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DoctrineORMByNameSpecification
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
            ->from(File::class, 'f')
            ->where($queryBuilder->expr()->eq('f.name.name', ':name'))
            ->andWhere($queryBuilder->expr()->eq('f.name.extension', ':extension'))
            ->setParameter('name', $this->name->name())
            ->setParameter('extension', $this->name->extension())
            ->getQuery();
    }
}
