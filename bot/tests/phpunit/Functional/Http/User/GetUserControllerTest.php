<?php
declare(strict_types=1);

namespace PHPUnit\Tests\Functional\Http\User;

use Bot\Application\User\Response\UserResponse;
use Bot\Infrastructure\Persistence\Doctrine\DataFixtures\ClientFixtures;
use Bot\Infrastructure\Persistence\Doctrine\DataFixtures\UserFixtures;
use PHPUnit\Tests\Functional\Http\FunctionalHttpTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetUserControllerTest extends FunctionalHttpTestBase
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

    public function testGetUserControllerSuccessResponse(): void
    {
        $this->prepareDatabase();
        $this->authenticatedClientA();

        self::$authenticatedClientA->request(
            Request::METHOD_GET,
            sprintf(self::END_POINT,UserFixtures::FRODO_UUID)
        );

        $response = self::$authenticatedClientA->getResponse();
        self::assertSame(Response::HTTP_OK,$response->getStatusCode());

        $responseData = json_decode($response->getContent(),true);

        self::assertSame(
            $responseData[UserResponse::CLIENT_ID],
            ClientFixtures::CLIENT_A_UUID
        );

        self::assertSame(
            $responseData[UserResponse::ID],
            UserFixtures::FRODO_UUID
        );
    }


}