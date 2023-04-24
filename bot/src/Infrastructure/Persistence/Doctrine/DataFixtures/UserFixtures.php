<?php

namespace Bot\Infrastructure\Persistence\Doctrine\DataFixtures;

use Bot\Domain\Client\Model\Client;
use Bot\Domain\Client\Model\Value\ClientPassword;
use Bot\Domain\Shared\Model\Value\Age;
use Bot\Domain\Shared\Model\Value\Email;
use Bot\Domain\Shared\Model\Value\LastName;
use Bot\Domain\Shared\Model\Value\Location;
use Bot\Domain\Shared\Model\Value\Name;
use Bot\Domain\Shared\Model\Value\Uuid;
use Bot\Domain\User\Model\User;
use Bot\Domain\User\Model\Value\UserCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture implements FixtureInterface
{
    const FRODO_UUID = '90d729d1-1e8e-411d-b3d0-4f5e8da1c886';
    const FRODO_NAME = 'Frodo';
    const FRODO_LASTNAME = 'Bolson';
    const FRODO_AGE = 85;
    const FRODO_CATEGORY = UserCategory::CATEGORY_X;
    const FRODO_ACTIVE = true;

    const FRODO_LOCATION = 'La Comarca';
    const FRODO_REFERENCE = 'user-frodo';


    public function __construct()
    {

    }

    public function load(ObjectManager $manager): void
    {

        /**
         * @var Client $adminRootRef ;
         */
        $clientARef = $this->getReference(ClientFixtures::CLIENT_A_REFERENCE);


        $frodo = $this->createUser(
            self::FRODO_UUID,
            self::FRODO_NAME,
            self::FRODO_LASTNAME,
            self::FRODO_LOCATION,
            self::FRODO_CATEGORY,
            self::FRODO_AGE,
            self::FRODO_ACTIVE,
            $clientARef
        );

        $manager->persist($frodo);
        $manager->flush();
        $this->addReference(self::FRODO_REFERENCE,$frodo);

    }

    private function createUser(
        string $id,
        string $name,
        string $lastName,
        string $location,
        string $category,
        int $age,
        bool $active,
        mixed $clientReference


    ): User
    {
       return new User(
           new Uuid($id),
           $clientReference,
           new Name($name),
           new LastName($lastName),
           new Age($age),
           new UserCategory($category),
           new Location($location),
           $active
       );

    }

}