<?php
declare(strict_types=1);

namespace PHPUnit\Tests\Functional\Http\User;

use Bot\Infrastructure\Persistence\Doctrine\DataFixtures\ClientFixtures;
use Bot\Infrastructure\Persistence\Doctrine\DataFixtures\UserFixtures;
use PHPUnit\Tests\Functional\Http\FunctionalHttpTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserControllerTest extends FunctionalHttpTestBase
{
    const END_POINT = 'api/v1/user/%s';

    public function setUp(): void
    {
        parent::setUp();
    }

    private function prepareDatabase(): void
    {
        $this->databaseTool->loadFixtures([
            ClientFixtures::class,
            UserFixtures::class
        ]);
    }

    public function testDeleteUserSuccessResponse(): void
    {
        $this->prepareDatabase();
        $this->authenticatedClientA();

        self::$authenticatedClientA->request(
            Request::METHOD_DELETE,
            sprintf(self::END_POINT,UserFixtures::FRODO_UUID)
        );

        $response = self::$authenticatedClientA->getResponse();
        self::assertSame(Response::HTTP_OK,$response->getStatusCode());

    }


}