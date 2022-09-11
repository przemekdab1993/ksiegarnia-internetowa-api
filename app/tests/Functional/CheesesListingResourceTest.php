<?php


namespace App\Tests\Functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\UserApi;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Component\DependencyInjection\Container;

class CheesesListingResourceTest extends ApiTestCase
{

    use ReloadDatabaseTrait;

    public function testCreateCheeseListing()
    {
//        $this->assertEquals(42, 42);

        $client = self::createClient();

        $client->request('POST', 'api/cheeses', [
            'headers' => [ 'Content-Type' => 'application/json'], 'json' => []
        ]);
        $this->assertResponseStatusCodeSame(401);

        $user = new UserApi();
        $user->setEmail('ewelina@gmail.com');
        $user->setUserName('ewelina');
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$sfRPoSLTSh2dGK9p/dUylA$sKgX+6VVZkbFrRGvwX3eOfgmUgN55lNiEt9l7YwcmXs');

        $em = self::$container->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $client->request('POST', '/login', [



            'headers' => [ 'Content-Type' => 'application/json'],
            'json' => [
                'email' => 'ewelina@gmail.com',
                'password' => 'qwerty'
            ]
        ]);
        $this->assertResponseStatusCodeSame(204);


    }
}