<?php

namespace App\Tests\Functional;

use App\Entity\CheeseListing;
use App\Entity\UserApi;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\DependencyInjection\Container;

class CheesesListingResourceTest extends CustomApiTestCase
{
    use ReloadDatabaseTrait;

    public function testCreateCheeseListing()
    {
        $client = self::createClient();

        $client->request('POST', 'api/cheeses', [
            'headers' => [ 'Content-Type' => 'application/json'],
            'json' => []
        ]);
        $this->assertResponseStatusCodeSame(401);

        $authenticatedUser = $this->createUserAndLogIn($client, 'ewelinakula@gmail.com', 'kula');
        $otherUser = $this->createUser('juje@gmail.com','sra');

        $cheesyData = [
            "title" => "stringoser",
            "price" => 2220,
            "quantity" => 10,
            "description" => "string"
        ];

        $client->request('POST', 'api/cheeses', [
            'headers' => [ 'Content-Type' => 'application/json'],
            'json' => $cheesyData
        ]);
        $this->assertResponseStatusCodeSame(201);



        $client->request('POST', 'api/cheeses', [
            'headers' => [ 'Content-Type' => 'application/json'],
            'json' => $cheesyData + [ 'owner' => '/api/user_apis/'.$otherUser->getId()]
        ]);
        $this->assertResponseStatusCodeSame(422, 'not passing the correct owner');


        $client->request('POST', 'api/cheeses', [
            'headers' => [ 'Content-Type' => 'application/json'],
            'json' => $cheesyData + [ 'owner' => '/api/user_apis/'.$authenticatedUser->getId()]
        ]);
        $this->assertResponseStatusCodeSame(201);

    }

    public function testUpdateCheeseListing()
    {
        $client = self::createClient();
        $user1 = $this->createUser('przemekd1@gmail.com', 'pomidor');
        $user2 = $this->createUserAdmin('przemekd2@gmail.com', 'pomidor');

        $cheeseListing = new CheeseListing('Dupka od sera');
        $cheeseListing->setPrice(2200);
        $cheeseListing->setQuantity(5);
        $cheeseListing->setDescription('Nie wiem co tu wpisać.');
        $cheeseListing->setOwner($user1);

        $em = $this->getEntityManager();
        $em->persist($cheeseListing);
        $em->flush();

        $this->logIn($client, 'przemekd1@gmail.com', 'pomidor');
        $client->request('PUT', '/api/cheeses/'.$cheeseListing->getId(), [
            'json' => ['quantity' => 100]
        ]);
        $this->assertResponseStatusCodeSame(200);


        $this->logIn($client, 'przemekd2@gmail.com', 'pomidor');
        $client->request('PUT', '/api/cheeses/'.$cheeseListing->getId(), [
            'json' => [
                'price' => 3333,
                'owner' => '/api/user_apis/'.$user2->getId()
            ]
        ]);

        $this->assertResponseStatusCodeSame(200);

    }
}