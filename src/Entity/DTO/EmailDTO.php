<?php

declare(strict_types=1);

namespace App\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class EmailDTO
{
    #[Assert\NotBlank]
    public $email;
}
