<?php
declare(strict_types=1);
namespace App\User\Model\Entity;

class User
{
    public function __construct(
        private ?int $id,
        private string $firstName,
        private string $lastName,
        private ?string $middleName,
        private ?string $gender,
        private ?string $birthDate,
        private string $email,
        private ?string $phone,
        private ?string $avatarPath
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getBirthDate(): ?string
    {
        return $this->birthDate;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getAvatarPath(): ?string
    {
        return $this->avatarPath;
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'middle_name' => $this->middleName,
            'gender' => $this->gender,
            'birth_date' => $this->birthDate,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar_path' => $this->avatarPath,
        ];
    }
}