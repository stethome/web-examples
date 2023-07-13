<?php

declare(strict_types=1);

namespace App\User\ApiResource;

use ApiPlatform\Metadata\Post;

#[Post('user/register')]
class UserRegisterDto
{
    public string $name;

    public string $surname;
}
