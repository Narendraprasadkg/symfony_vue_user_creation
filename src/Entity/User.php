<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16)]
    private ?string $customerNumber = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $anrede = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $secondName = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $street = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $houseNumber = null;

    #[ORM\Column(length: 24, nullable: true)]
    private ?string $pin = null;

    #[ORM\Column(length: 600, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerNumber(): ?string
    {
        return $this->customerNumber;
    }

    public function setCustomerNumber(string $customerNumber): static
    {
        $this->customerNumber = $customerNumber;

        return $this;
    }

    public function getAnrede(): ?string
    {
        return $this->anrede;
    }

    public function setAnrede(?string $anrede): static
    {
        $this->anrede = $anrede;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSecondName(): ?string
    {
        return $this->secondName;
    }

    public function setSecondName(?string $secondName): static
    {
        $this->secondName = $secondName;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(?string $houseNumber): static
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    public function getPin(): ?string
    {
        return $this->pin;
    }

    public function setPin(?string $pin): static
    {
        $this->pin = $pin;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
}
