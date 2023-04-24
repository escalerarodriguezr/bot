<?php

namespace Bot\Infrastructure\Persistence\Doctrine\DataFixtures;

use Bot\Domain\Client\Model\Client;
use Bot\Domain\Client\Model\Value\ClientPassword;
use Bot\Domain\Shared\Model\Value\Email;
use Bot\Domain\Shared\Model\Value\Name;
use Bot\Domain\Shared\Model\Value\Uuid;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ClientFixtures extends Fixture implements FixtureInterface
{
    const CLIENT_A_UUID = '03df8a4e-4598-4033-9bbf-8cd90d7b1f99';
    const CLIENT_A_EMAIL = 'client_A_@bot.com';
    const CLIENT_A_NAME = 'Cliente A';
    const CLIENT_A_PASSWORD = 'client_a_password';
    const CLIENT_A_REFERENCE = 'client-a';


    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {

    }

    public function load(ObjectManager $manager): void
    {
        $clientA = $this->createClient(
            self::CLIENT_A_UUID,
            self::CLIENT_A_EMAIL,
            self::CLIENT_A_NAME,
            self::CLIENT_A_PASSWORD
        );

        $manager->persist($clientA);
        $manager->flush();
        $this->addReference(self::CLIENT_A_REFERENCE, $clientA);

    }

    private function createClient(
        string $id,
        string $email,
        string $name,
        string $password

    ): Client
    {
       $client = new Client(
           new Uuid($id),
           new Email($email),
           new Name($name)
       );

        $passwordValue = new ClientPassword($password);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $client,
            $passwordValue->value
        );

        $client->setPassword($hashedPassword);
        return $client;
    }

}