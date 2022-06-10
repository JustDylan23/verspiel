<?php

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordResetDTO
{
    #[Assert\NotBlank]
    public $token;

    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    public $password;

    #[Assert\NotBlank]
    #[Assert\EqualTo(propertyPath: 'password', message: 'Passwords do not match.')]
    public $confirmPassword;
}
