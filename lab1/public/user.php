<?php

class User
{
    public function __construct(
        private ?int    $id,
        private string  $firstName,
        private string  $lastName,
        private ?string $middleName,
        private ?string $gender,
        private ?string $birthDate,
        private string  $email,
        private ?string $phone,
        private ?string $avatarPath
    ) {}

    // Геттеры 
    public function getId(): ?int { return $this->id; }
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getMiddleName(): ?string { return $this->middleName; }
    public function getGender(): ?string { return $this->gender; }
    public function getBirthDate(): ?string { return $this->birthDate; }
    public function getEmail(): string { return $this->email; }
    public function getPhone(): ?string { return $this->phone; }
    public function getAvatarPath(): ?string { return $this->avatarPath; }

    // Сеттеры
    public function setId(int $id): void { $this->id = $id; }
    public function setFirstName(string $firstName): void { $this->firstName = $firstName; }
    public function setLastName(string $lastName): void { $this->lastName = $lastName; }
    public function setMiddleName(?string $middleName): void { $this->middleName = $middleName; }
    public function setGender(?string $gender): void { $this->gender = $gender; }
    public function setBirthDate(?string $birthDate): void { $this->birthDate = $birthDate; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setPhone(?string $phone): void { $this->phone = $phone; }
    public function setAvatarPath(?string $avatarPath): void { $this->avatarPath = $avatarPath; }

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