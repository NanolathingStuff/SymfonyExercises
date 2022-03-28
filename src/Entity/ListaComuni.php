<?php

namespace App\Entity;

use App\Repository\ListaComuniRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListaComuniRepository::class)]
class ListaComuni
{
    /*#[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;*/

    #[ORM\Column(type: 'string', length: 255)]
    private $Comune;

    #[ORM\Column(type: 'string', length: 2)]
    private $Provincia;

    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private $CodFisco;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComune(): ?string
    {
        return $this->Comune;
    }

    public function setComune(string $Comune): self
    {
        $this->Comune = $Comune;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->Provincia;
    }

    public function setProvincia(string $Provincia): self
    {
        $this->Provincia = $Provincia;

        return $this;
    }

    public function getCodFisco(): ?string
    {
        return $this->CodFisco;
    }

    public function setCodFisco(?string $CodFisco): self
    {
        $this->CodFisco = $CodFisco;

        return $this;
    }
}
