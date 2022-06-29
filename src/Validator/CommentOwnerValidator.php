<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CommentOwnerValidator extends ConstraintValidator
{
    public function __construct(private readonly Security $security)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof CommentOwner) {
            throw new UnexpectedTypeException($constraint, CommentOwner::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->security->getUser() !== $value && !$this->security->isGranted('ROLE_MODERATOR')) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
