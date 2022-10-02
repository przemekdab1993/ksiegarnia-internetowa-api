<?php

namespace App\Tests\Functional;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreateUser()
    {
        $client = self::createClient();

        $client->request('POST', '/api/user_apis', [
            'json' => [
                'email' => 'user@example.com',
                'password' => 'string',
                'userName' => 'userXD',
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);

        $this->logIn($client, 'user@example.com', 'string');
    }

    public function testUpdateUser()
    {
        $client = self::createClient();

        $user= $this->createUserAndLogIn($client, 'pola@gmail.com', 'duda');

        $client->request('PUT', '/api/user_apis/'.$user->getId(), [
            'json' => [
                'userName' => 'userXD',
            ]
        ]);

        $this->assertResponseIsSuccessful();
//        $this->assertJsonContains([
//            'username' => 'userXD'
//        ]);

    }
}