<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolationList;

class InvalidEntityException extends UnprocessableEntityHttpException
{
    public function __construct(private readonly ConstraintViolationList $violations)
    {
        parent::__construct('Invalid entity');
    }

    public function getViolations(): ConstraintViolationList
    {
        return $this->violations;
    }
}
