<?php

declare(strict_types=1);

namespace App\Serializer;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class SingleIdentifierEntityDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    private const ALREADY_CALLED = 'SINGLE_IDENTIFIER_ENTITY_NORMALIZER_ALREADY_CALLED';

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
            && !is_array($data)
            && !empty($this->em->getClassMetadata($type)->getSingleIdentifierFieldName());
    }

    /**
     * {@inheritDoc}
     * @throws ORMException
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): mixed
    {
        $context[self::ALREADY_CALLED] = true;
        if (!is_int($data)) {
            throw new UnexpectedValueException();
        }
        $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $this->em->getReference($type, $data);

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }
}
