<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Ui\Http\Request\DTO\User;

use Bot\Domain\User\Model\Value\UserCategory;
use Bot\Infrastructure\Ui\Http\Request\RequestDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Contracts\Service\Attribute\Required;

class CreateUserRequest implements RequestDTO
{
    const NAME = 'name';
    const LAST_NAME = 'lastName';
    const LOCATION = 'location';
    const ACTIVE = 'active';
    const AGE = 'age';
    const CATEGORY = 'category';

    const CATEGORY_CHOICES = [
        UserCategory::CATEGORY_X,
        UserCategory::CATEGORY_Y,
        UserCategory::CATEGORY_Z
    ];


    #[NotBlank()]
    #[Length(
        min: 2,
        max: 100,
        minMessage: 'name must be at least {{ limit }} characters long',
        maxMessage: 'name cannot be longer than {{ limit }} characters',
    )]
    private mixed $name;

    #[NotBlank()]
    #[Length(
        min: 2,
        max: 100,
        minMessage: 'lastName must be at least {{ limit }} characters long',
        maxMessage: 'lastName cannot be longer than {{ limit }} characters',
    )]
    private mixed $lastName;

    #[NotBlank()]
    #[Length(
        min: 2,
        max: 100,
        minMessage: 'location must be at least {{ limit }} characters long',
        maxMessage: 'location cannot be longer than {{ limit }} characters',
    )]
    private mixed $location;

    #[NotBlank()]
    #[Choice(
        choices: self::CATEGORY_CHOICES,
        message: 'Choose a valid category'
    )]
    private mixed $category;

    #[NotNull()]
    private mixed $active;

    #[NotBlank()]
    #[Type(
        type: "integer",
        message: 'age must be integer number'
    )]
    #[Positive(
        message: 'age must be positive number'
    )]
    private mixed $age;



    public function __construct(Request $request)
    {
        $payload = json_decode($request->getContent(),true);
        $this->name = $payload[self::NAME] ?? null;
        $this->lastName = $payload[self::LAST_NAME] ?? null;
        $this->location = $payload[self::LOCATION] ?? null;
        $this->age = $payload[self::AGE] ?? null;
        $this->category = $payload[self::CATEGORY] ?? null;
        $this->active = $payload[self::ACTIVE] ?? null;
        if($this->active !== null){
            $this->active = filter_var($this->active, FILTER_VALIDATE_BOOL);
        }

    }


    public function getName(): string
    {
        return $this->name;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function getAge(): int
    {
        return $this->age;
    }


    public function getCategory(): string
    {
        return $this->category;
    }





}