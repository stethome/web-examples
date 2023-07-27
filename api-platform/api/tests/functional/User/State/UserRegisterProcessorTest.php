<?php

declare(strict_types=1);

namespace App\Tests\functional\User\State;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\User\ApiResource\UserModel;

class UserRegisterProcessorTest extends ApiTestCase
{
    public function testRegisterUser(): void
    {
        self::createClient()->request('POST', '/user_models', ['json' => [
            'email' => 'user@stethome.com',
            'name' => 'John',
            'surname' => 'Doe',
            'password' => 'L0ngS3cur3P4ssw0rd!',
        ]]);

        self::assertResponseStatusCodeSame(201);
        self::assertMatchesResourceItemJsonSchema(UserModel::class);
        self::assertJsonContains([
            '@context' => '/contexts/UserModel',
            '@type' => 'UserModel',
            'email' => 'user@stethome.com',
            'name' => 'John',
            'surname' => 'Doe',
        ]);
    }
}
