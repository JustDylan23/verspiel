<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractRestController extends AbstractController
{
    protected const ITEM_ATTRIBUTES = ['id'];
    protected const LIST_ATTRIBUTES = ['id'];
    protected const WRITE_ATTRIBUTES = [];

    public function __construct(
        protected RequestStack $requestStack,
        protected SerializerInterface $serializer,
        protected NormalizerInterface $normalizer,
        protected EntityManagerInterface $entityManager,
        protected ValidatorInterface $validator
    ) {
    }

    /**
     * @param array<string, mixed>|null $attributes
     */
    protected function viewItem(?object $data, array $attributes = null): array
    {
        if (null === $data) {
            throw $this->createNotFoundException();
        }

        return $this->toArray($data, $attributes ?? static::ITEM_ATTRIBUTES);
    }

    protected function toArray($data, array $attributes): array
    {
        return $this->normalizer->normalize($data, null, ['attributes' => $attributes]);
    }

    protected function viewList($data, array $attributes = null): array
    {
        return $this->toArray($data, $attributes ?? static::LIST_ATTRIBUTES);
    }

    protected function viewCreate($data, array $attributes = null): array
    {
        $this->entityManager->persist($data);

        return $this->viewPatch($data, $attributes);
    }

    protected function viewPatch($data, array $attributes = null): array
    {
        try {
            $this->entityManager->flush();
        } catch (ORMInvalidArgumentException) {
            throw new BadRequestHttpException('Invalid lifecycle state error');
        } catch (ConstraintViolationException) {
            throw new BadRequestHttpException('Database error');
        }

        return $this->toArray($data, $attributes ?? static::ITEM_ATTRIBUTES);
    }

    protected function viewDelete($data): void
    {
        if (null === $data) {
            throw $this->createNotFoundException();
        }

        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }

    protected function deserializeRequestContent(object $object)
    {
        try {
            return $this->serializer->deserialize(
                $this->requestStack->getCurrentRequest()->getContent(),
                $object::class,
                'json',
                [
                    'attributes' => static::WRITE_ATTRIBUTES,
                    AbstractNormalizer::OBJECT_TO_POPULATE => $object,
                ]
            );
        } catch (UnexpectedValueException) {
            throw new BadRequestHttpException('Malformed request');
        }
    }

    protected function getViolations($data): ConstraintViolationListInterface
    {
        return $this->validator->validate($data);
    }
}
