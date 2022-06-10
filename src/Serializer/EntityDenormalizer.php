<?php

declare(strict_types=1);

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class EntityDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const ALREADY_CALLED = 'ENTITY_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {

        return !isset($context[self::ALREADY_CALLED])
            && !empty((new \ReflectionClass($type))->getAttributes(Entity::class))
            && is_array($data)
            && !array_diff($this->em->getClassMetadata($type)->getIdentifier(), array_keys($data));
    }

    /**
     * {@inheritDoc}
     * @throws ORMException
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): mixed
    {
        $context[self::ALREADY_CALLED] = true;
        $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $this->em->getReference($type, array_map(fn($el) => $data[$el], $this->em->getClassMetadata($type)->getIdentifier()));

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }
}
