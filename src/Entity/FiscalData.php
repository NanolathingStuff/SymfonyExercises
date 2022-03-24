<?php

namespace App\Entity;

use App\Repository\FiscalDataRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FiscalDataRepository::class)]
class FiscalData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $surname;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'boolean')]
    private $gender;

    #[ORM\Column(type: 'string', length: 255)]
    private $born_place;

    #[ORM\Column(type: 'string', length: 2)]
    private $province;

    #[ORM\Column(type: 'date')]
    private $birth_day;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGender(): ?bool
    {
        return $this->gender;
    }

    public function setGender(bool $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getBornPlace(): ?string
    {
        return $this->born_place;
    }

    public function setBornPlace(string $born_place): self
    {
        $this->born_place = $born_place;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getBirthDay(): ?\DateTimeInterface
    {
        return $this->birth_day;
    }

    public function setBirthDay(\DateTimeInterface $birth_day): self
    {
        $this->birth_day = $birth_day;

        return $this;
    }
}
