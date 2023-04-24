<?php
declare(strict_types=1);

namespace PHPUnit\Tests\Functional\Http\User;

use Bot\Domain\User\Model\Value\UserCategory;
use Bot\Infrastructure\Persistence\Doctrine\DataFixtures\ClientFixtures;
use Bot\Infrastructure\Ui\Http\Request\DTO\User\CreateUserRequest;
use PHPUnit\Tests\Functional\Http\FunctionalHttpTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateUserControllerTest extends FunctionalHttpTestBase
{
    const END_POINT = 'api/v1/user';

    public function setUp(): void
    {
        parent::setUp();
    }

    private function prepareDatabase(): void
    {
        $this->databaseTool->loadFixtures([
            ClientFixtures::class
        ]);
    }

    public function testCreateUserControllerSuccessResponse(): void
    {
        $this->prepareDatabase();
        $this->authenticatedClientA();

        $payload = [
            CreateUserRequest::NAME => "Rafa",
            CreateUserRequest::LAST_NAME => "Perez",
            CreateUserRequest::LOCATION => "Requejada",
            CreateUserRequest::ACTIVE => false,
            CreateUserRequest::AGE => 36,
            CreateUserRequest::CATEGORY => UserCategory::CATEGORY_X

        ];

        self::$authenticatedClientA->request(
            Request::METHOD_POST,
            self::END_POINT,
            [],[],[],
            json_encode($payload)
        );

        $response = self::$authenticatedClientA->getResponse();

        self::assertSame(Response::HTTP_CREATED,$response->getStatusCode());

    }

    public function testCreateUserController422Response(): void
    {
        $this->prepareDatabase();
        $this->authenticatedClientA();

        $payload = [
            CreateUserRequest::NAME => "",
            CreateUserRequest::LAST_NAME => "",
            CreateUserRequest::LOCATION => "",
            CreateUserRequest::ACTIVE => "",
            CreateUserRequest::AGE => "",
            CreateUserRequest::CATEGORY => 'fake'

        ];

        self::$authenticatedClientA->request(
            Request::METHOD_POST,
            self::END_POINT,
            [],[],[],
            json_encode($payload)
        );

        $response = self::$authenticatedClientA->getResponse();
        self::assertSame(Response::HTTP_UNPROCESSABLE_ENTITY,$response->getStatusCode());

    }


}