<?php

declare(strict_types=1);

namespace App\Doctrine;

use App\Entity\Chapter;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class PublishedFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if ($targetEntity->reflClass->name === Chapter::class) {
            return $targetTableAlias.'.published = 1';
        }

        return '';
    }
}
