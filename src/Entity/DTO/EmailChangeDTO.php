<?php

declare(strict_types=1);

namespace App\Entity\DTO;

use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

class EmailChangeDTO
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public $email;

    #[UserPassword]
    public $password;
}
