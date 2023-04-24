<?php
declare(strict_types=1);

namespace Bot\Domain\Client\Model;

use Bot\Domain\Shared\Model\AggregateRoot;
use Bot\Domain\Shared\Model\Value\Email;
use Bot\Domain\Shared\Model\Value\Name;
use Bot\Domain\Shared\Model\Value\Uuid;
use Bot\Domain\User\Model\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class Client extends AggregateRoot implements UserInterface, PasswordAuthenticatedUserInterface
{
    private string $id;
    private string $email;
    private string $name;
    private ?string $password;
    private Collection $users;



    public function __construct(
        Uuid $id,
        Email $email,
        Name $name
    )
    {
        parent::__construct();

        $this->id = $id->value;
        $this->email = $email->value;
        $this->name = $name->value;
        $this->password = null;
        $this->users = new ArrayCollection();

    }

    public function getRoles(): array
    {
      return [];
    }

    public function eraseCredentials():void
    {

    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getId(): Uuid
    {
        return new Uuid($this->id);
    }

    public function getEmail(): Email
    {
        return new Email($this->email);
    }




    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email->value;
    }


    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getName(): Name
    {
        return new Name($this->name);
    }

    public function setName(Name $name): void
    {
        $this->name = $name->value;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): void
    {
        if($this->users->contains($user)){
            return;
        }
        $this->users->add($user);
    }






}