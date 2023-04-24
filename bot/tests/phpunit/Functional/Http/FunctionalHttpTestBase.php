<?php
declare(strict_types=1);

namespace PHPUnit\Tests\Functional\Http;

use Bot\Domain\Client\Repository\ClientRepository;
use Bot\Infrastructure\Persistence\Doctrine\DataFixtures\ClientFixtures;
use Doctrine\DBAL\Connection;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FunctionalHttpTestBase extends WebTestCase
{
    private static ?KernelBrowser $client = null;
    protected static ?KernelBrowser $baseClient = null;
    protected static ?KernelBrowser $authenticatedClientA = null;


    protected mixed $databaseTool;


    public function setUp():void
    {
        parent::setUp();
        $this->getClient();
        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    protected function getClient():void
    {
        self::$client = null;
        if (null === self::$client) {
            self::$client = static::createClient();
        }
    }

    protected function baseClient():void
    {
        self::$baseClient = null;
        if (null === self::$baseClient) {
            self::$baseClient = clone self::$client;
            self::$baseClient->setServerParameters([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
            ]);
        }
    }

    protected static function initDBConnection(): Connection
    {
        if (null === static::$kernel) {
            static ::bootKernel();
        }

        return static::$kernel->getContainer()->get('doctrine')->getConnection();
    }

    protected function authenticatedClientA():void
    {
        self::$authenticatedClientA = null;
        if (null === self::$authenticatedClientA) {
            self::$authenticatedClientA = clone self::$client;

            $user = static::getContainer()->get(ClientRepository::class)->findByEmail(ClientFixtures::CLIENT_A_EMAIL);
            $token = static::getContainer()->get(JWTTokenManagerInterface::class)->create($user);

            self::$authenticatedClientA->setServerParameters([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_Authorization' => \sprintf('Bearer %s', $token),
            ]);
        }
    }

}