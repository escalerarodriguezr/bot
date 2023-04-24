<?php
declare(strict_types=1);

namespace Bot\Domain\User\Model;

use Bot\Domain\Client\Model\Client;
use Bot\Domain\Shared\Model\AggregateRoot;
use Bot\Domain\Shared\Model\Value\Age;
use Bot\Domain\Shared\Model\Value\LastName;
use Bot\Domain\Shared\Model\Value\Location;
use Bot\Domain\Shared\Model\Value\Name;
use Bot\Domain\Shared\Model\Value\Uuid;
use Bot\Domain\User\Model\Value\UserCategory;

class User extends AggregateRoot
{
    private string $id;
    private Client $client;
    private string $name;
    private string $lastName;
    private int $age;
    private string $category;
    private string $location;
    private bool $active;

    public function __construct(
        Uuid $id,
        Client $client,
        Name $name,
        LastName $lastName,
        Age $age,
        UserCategory $category,
        Location $location,
        bool $active
    )
    {
        parent::__construct();
        $this->id = $id->value;
        $this->client = $client;
        $this->name = $name->value;
        $this->lastName = $lastName->value;
        $this->age = $age->value;
        $this->category = $category->value;
        $this->location = $location->value;
        $this->active = $active;
    }

    public function getId(): Uuid
    {
        return new Uuid($this->id);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function getName(): Name
    {
        return new Name($this->name);
    }

    public function setName(Name $name): self
    {
        $this->name = $name->value;
        return $this;
    }

    public function getLastName(): LastName
    {
        return new LastName($this->lastName);
    }

    public function setLastName(LastName $lastName): self
    {
        $this->lastName = $lastName->value;
        return $this;
    }

    public function getAge(): Age
    {
        return new Age($this->age);
    }

    public function setAge(Age $age): self
    {
        $this->age = $age->value;
        return $this;
    }

    public function getCategory(): UserCategory
    {
        return new UserCategory($this->category);
    }

    public function setCategory(UserCategory $category): self
    {
        $this->category = $category->value;
        return $this;
    }

    public function getLocation(): Location
    {
        return new Location($this->location);
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location->value;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

}