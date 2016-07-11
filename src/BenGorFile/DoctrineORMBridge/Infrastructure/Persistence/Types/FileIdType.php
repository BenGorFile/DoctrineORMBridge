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

namespace BenGorFile\DoctrineORMBridge\Infrastructure\Persistence\Types;

use BenGorFile\File\Domain\Model\FileId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

/**
 * Doctrine ORM file id custom type class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FileIdType extends GuidType
{
    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof FileId) {
            return $value->id();
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new FileId($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'file_id';
    }
}
