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

namespace spec\BenGorFile\DoctrineORMBridge\Infrastructure\Persistence;

use BenGorFile\DoctrineORMBridge\Infrastructure\Persistence\DoctrineORMFileRepository;
use BenGorFile\File\Domain\Model\File;
use BenGorFile\File\Domain\Model\FileId;
use BenGorFile\File\Domain\Model\FileName;
use BenGorFile\File\Domain\Model\FileRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Persisters\Entity\EntityPersister;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnitOfWork;
use PhpSpec\ObjectBehavior;

/**
 * Spec file of DoctrineORMFileRepository class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DoctrineORMFileRepositorySpec extends ObjectBehavior
{
    function let(EntityManager $manager, ClassMetadata $metadata)
    {
        $this->beConstructedWith($manager, $metadata);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DoctrineORMFileRepository::class);
    }

    function it_implements_file_doctrine_repository()
    {
        $this->shouldImplement(FileRepository::class);
    }

    function it_extends_entity_repository()
    {
        $this->shouldHaveType(EntityRepository::class);
    }

    function it_get_file_of_id(File $file, EntityManager $manager)
    {
        $manager->find(null, 'file-id', null, null)->shouldBeCalled()->willReturn($file);

        $this->fileOfId(new FileId('file-id'))->shouldReturn($file);
    }

    function it_get_file_of_name(
        File $file,
        EntityManager $manager,
        UnitOfWork $unitOfWork,
        EntityPersister $entityPersister
    ) {
        $manager->getUnitOfWork()->shouldBeCalled()->willReturn($unitOfWork);
        $unitOfWork->getEntityPersister(null)->shouldBeCalled()->willReturn($entityPersister);
        $entityPersister->load(
            ['name.name' => 'my-pdf-file', 'name.extension' => 'pdf'],
            null,
            null,
            [],
            null,
            1,
            null
        )->shouldBeCalled()->willReturn($file);

        $this->fileOfName(new FileName('my-pdf-file.pdf'))->shouldReturn($file);
    }

    function it_persist(EntityManager $manager, File $file)
    {
        $manager->persist($file)->shouldBeCalled();

        $this->persist($file);
    }

    function it_remove(EntityManager $manager, File $file)
    {
        $manager->remove($file)->shouldBeCalled();

        $this->remove($file);
    }

    function it_gets_the_file_table_size(
        EntityManager $manager,
        QueryBuilder $queryBuilder,
        Expr $expr,
        Expr\Func $func,
        AbstractQuery $query
    ) {
        $manager->createQueryBuilder()->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->expr()->shouldBeCalled()->willReturn($expr);
        $queryBuilder->select('f')->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->from(null, 'f', null)->shouldBeCalled()->willReturn($queryBuilder);

        $func->__toString()->willReturn('COUNT(file.id.id)');
        $expr->count('f.id.id')->shouldBeCalled()->willReturn($func);
        $queryBuilder->select($func)->shouldBeCalled()->willReturn($queryBuilder);
        $queryBuilder->getQuery()->shouldBeCalled()->willReturn($query);
        $query->getSingleScalarResult()->shouldBeCalled()->willReturn(2);

        $this->size()->shouldReturn(2);
    }
}
