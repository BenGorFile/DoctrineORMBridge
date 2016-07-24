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
use BenGorFile\File\Domain\Model\FileId;
use BenGorFile\File\Domain\Model\FileName;
use BenGorFile\File\Domain\Model\FileRepository;
use Doctrine\ORM\EntityRepository;

/**
 * Doctrine ORM file repository class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class DoctrineORMFileRepository extends EntityRepository implements FileRepository
{
    /**
     * {@inheritdoc}
     */
    public function fileOfId(FileId $anId)
    {
        return $this->find($anId->id());
    }

    /**
     * {@inheritdoc}
     */
    public function fileOfName(FileName $aName)
    {
        return $this->findOneBy(['name.name' => $aName->name(), 'name.extension' => $aName->extension()]);
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function persist(File $aFile)
    {
        $this->getEntityManager()->persist($aFile);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(File $aFile)
    {
        $this->getEntityManager()->remove($aFile);
    }

    /**
     * {@inheritdoc}
     */
    public function size()
    {
        $queryBuilder = $this->createQueryBuilder('f');

        return $queryBuilder
            ->select($queryBuilder->expr()->count('f.id.id'))
            ->getQuery()
            ->getSingleScalarResult();
    }
}
