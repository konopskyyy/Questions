<?php

declare(strict_types=1);

namespace App\User\UserInterface\Controller\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 7, max: 100)]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 7, max: 100)]
    public string $password;
}
