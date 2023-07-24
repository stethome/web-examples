<?php

declare(strict_types=1);

namespace App\User\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserRegisterDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public $password;

    #[Assert\NotBlank]
    public $name;

    #[Assert\NotBlank]
    public $surname;
}
