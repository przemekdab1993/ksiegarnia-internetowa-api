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

        $this->createUserAndLogIn($client, 'ewelinakula@gmail.com', 'kula');

        $client->request('POST', 'api/cheeses', [
            'headers' => [ 'Content-Type' => 'application/json'],
            'json' => []
        ]);
        $this->assertResponseStatusCodeSame(422);

    }

    public function testUpdateCheeseListing()
    {
        $client = self::createClient();
        $user1 = $this->createUser('przemekd1@gmail.com', 'pomidor');
        $user2 = $this->createUser('przemekd2@gmail.com', 'pomidor');

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
            'json' => ['title' => 'updated1']
        ]);
        $this->assertResponseStatusCodeSame(200);


        $this->logIn($client, 'przemekd2@gmail.com', 'pomidor');
        $client->request('PUT', '/api/cheeses/'.$cheeseListing->getId(), [
            'json' => ['title' => 'updated2']
        ]);

        $this->assertResponseStatusCodeSame(403);
        var_dump($client->getResponse()->getContent(false));

    }
}