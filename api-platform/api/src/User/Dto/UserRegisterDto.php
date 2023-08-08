<?php

declare(strict_types=1);

namespace App\User\Dto;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterDto
{
    #[ApiProperty(example: 'user@example.com')]
    #[Assert\NotBlank]
    #[Assert\Email]
    public $email;

    #[ApiProperty(example: 'password')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public $password;

    #[Assert\NotBlank]
    public $name;

    #[Assert\NotBlank]
    public $surname;
}
