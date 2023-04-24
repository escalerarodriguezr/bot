<?php
declare(strict_types=1);

namespace PHPUnit\Tests\Functional\Http\User;

use Bot\Domain\User\Model\Value\UserCategory;
use Bot\Infrastructure\Persistence\Doctrine\DataFixtures\ClientFixtures;
use Bot\Infrastructure\Persistence\Doctrine\DataFixtures\UserFixtures;
use Bot\Infrastructure\Ui\Http\Request\DTO\User\UpdateUserRequest;
use PHPUnit\Tests\Functional\Http\FunctionalHttpTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserControllerTest extends FunctionalHttpTestBase
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

    public function testUpdateUserSuccessResponse(): void
    {
        $this->prepareDatabase();
        $this->authenticatedClientA();

        $payload = [
            UpdateUserRequest::NAME => 'Name2',
            UpdateUserRequest::LAST_NAME => 'LastName2',
            UpdateUserRequest::LOCATION => 'Location2',
            UpdateUserRequest::CATEGORY => UserCategory::CATEGORY_Z,
            UpdateUserRequest::AGE => 25,
            UpdateUserRequest::ACTIVE => false

        ];

        self::$authenticatedClientA->request(
            Request::METHOD_PUT,
            sprintf(self::END_POINT,UserFixtures::FRODO_UUID),
            [],[],[],
            json_encode($payload)
        );

        $response = self::$authenticatedClientA->getResponse();
        self::assertSame(Response::HTTP_OK,$response->getStatusCode());
    }

    public function testUpdateUser422Response(): void
    {
        $this->prepareDatabase();
        $this->authenticatedClientA();

        $payload = [
            UpdateUserRequest::NAME => '',
            UpdateUserRequest::LAST_NAME => '',
            UpdateUserRequest::LOCATION => '',
            UpdateUserRequest::CATEGORY => 'fake',
            UpdateUserRequest::AGE => 500,
            UpdateUserRequest::ACTIVE => 'fake'

        ];

        self::$authenticatedClientA->request(
            Request::METHOD_PUT,
            sprintf(self::END_POINT,UserFixtures::FRODO_UUID),
            [],[],[],
            json_encode($payload)
        );

        $response = self::$authenticatedClientA->getResponse();
        self::assertSame(Response::HTTP_UNPROCESSABLE_ENTITY,$response->getStatusCode());
    }


}