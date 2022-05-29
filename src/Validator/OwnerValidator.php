<?php

namespace App\Validator;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class OwnerValidator extends ConstraintValidator
{
    public function __construct(private readonly Security $security)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Owner) {
            throw new UnexpectedTypeException($constraint, Owner::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->security->getUser() !== $value) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
