<?php

declare(strict_types=1);

namespace App\Tests\functional\User\ApiResource;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\User\ApiResource\UserModel;

class UserModelTest extends ApiTestCase
{
    public function testRegister(): void
    {
        self::createClient()->request('POST', '/api/register', ['json' => [
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
