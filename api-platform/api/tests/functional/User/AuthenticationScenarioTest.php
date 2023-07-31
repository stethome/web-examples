<?php

declare(strict_types=1);

namespace App\Tests\functional\User;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\User\ApiResource\UserModel;
use App\User\Entity\SecurityUser;
use App\User\Entity\UserData;
use Symfony\Component\Uid\Factory\UuidFactory;
use Symfony\Component\Uid\Uuid;

class AuthenticationScenarioTest extends ApiTestCase
{
    public function testLogin(): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        /** @var UuidFactory $uuidFactory */
        $uuidFactory = $container->get('uuid.factory');

        $uuidUser = $uuidFactory->create();
        $user = new SecurityUser(
            $uuidUser,
            new UserData($uuidFactory->create(), 'John', 'Login'),
            'login@stethome.com',
        );
        $user->setPassword(
            $container->get('security.user_password_hasher')->hashPassword($user, '$3CR3T')
        );

        $manager = $container->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        // retrieve a token
        $response = $client->request('POST', '/api/auth', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'login@stethome.com',
                'password' => '$3CR3T',
            ],
        ]);
        $json = $response->toArray();

        self::assertResponseIsSuccessful();
        self::assertMatchesJsonSchema('{
          "$schema": "http://json-schema.org/draft-04/schema#",
          "type": "object",
          "properties": {
            "token": {
              "type": "string"
            }
          },
          "required": [
            "token"
          ]
        }');

        // @todo test JWT payload

        // test not authorized
        $client->request('GET', '/api/user_models/'.$uuidUser);
        self::assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/api/user_models/'.$uuidUser, ['auth_bearer' => $json['token']]);
        self::assertResponseIsSuccessful();
        self::assertMatchesResourceItemJsonSchema(UserModel::class);
    }
}
