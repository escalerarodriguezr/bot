<?php
declare(strict_types=1);

namespace PHPUnit\Tests\Functional\Http\User;

use Bot\Application\User\Response\UserResponse;
use Bot\Domain\Shared\Repository\AdvancedFilter;
use Bot\Domain\User\Repository\SearchUserFilters;
use Bot\Infrastructure\Persistence\Doctrine\DataFixtures\ClientFixtures;
use Bot\Infrastructure\Persistence\Doctrine\DataFixtures\UserFixtures;
use PHPUnit\Tests\Functional\Http\FunctionalHttpTestBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchUserControllerTest extends FunctionalHttpTestBase
{
    const END_POINT = 'api/v1/users';

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

    public function testSearchUserControllerAllActive(): void
    {
        $this->prepareDatabase();
        $this->authenticatedClientA();

        $filters = [
            'activeCondition'.AdvancedFilter::EQ => true,
        ];

        self::$authenticatedClientA->request(
            Request::METHOD_GET,
            self::END_POINT,
            $filters
        );

        $response = self::$authenticatedClientA->getResponse();
        self::assertSame(Response::HTTP_OK,$response->getStatusCode());

        $responseData = json_decode($response->getContent(),true);

        list($first) = $responseData;
        self::assertSame(
            $first[UserResponse::CLIENT_ID],
            ClientFixtures::CLIENT_A_UUID
        );

    }

    public function testSearchUserControllerAllNotActive(): void
    {
        $this->prepareDatabase();
        $this->authenticatedClientA();

        $filters = [
            'activeCondition'.AdvancedFilter::NOTEQ => true,
        ];

        self::$authenticatedClientA->request(
            Request::METHOD_GET,
            self::END_POINT,
            $filters
        );

        $response = self::$authenticatedClientA->getResponse();
        self::assertSame(Response::HTTP_OK,$response->getStatusCode());

        $responseData = json_decode($response->getContent(),true);

        self::assertSame(count($responseData),0);

    }


}