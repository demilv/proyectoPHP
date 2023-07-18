<?php

namespace App\Entity;

use App\Repository\TaxiRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: TaxiRepository::class)]
class Taxi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $marca = null;

    #[ORM\Column(nullable: true)]
    private ?int $velocidad = null;

    #[ORM\Column(nullable: true)]
    private ?bool $activo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $propietario = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modelo = null;

    #[ORM\Column(length: 255)]
    private ?string $localizacion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarca(): ?string
    {
        return $this->marca;
    }

    public function setMarca(string $marca): static
    {
        $this->marca = $marca;

        return $this;
    }

    public function getVelocidad(): ?int
    {
        return $this->velocidad;
    }

    public function setVelocidad(?int $velocidad): static
    {
        $this->velocidad = $velocidad;

        return $this;
    }

    public function isActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(?bool $activo): static
    {
        $this->activo = $activo;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getPropietario(): ?string
    {
        return $this->propietario;
    }

    public function setPropietario(string $propietario): static
    {
        $this->propietario = $propietario;

        return $this;
    }

    public function getModelo(): ?string
    {
        return $this->modelo;
    }

    public function setModelo(?string $modelo): static
    {
        $this->modelo = $modelo;

        return $this;
    }

    public function getLocalizacion(): ?string
    {
        return $this->localizacion;
    }

    public function setLocalizacion(string $localizacion): static
    {
        $this->localizacion = $localizacion;

        return $this;
    }

    public static function buscarTaxiActivoEnZona(string $localizacion, TaxiRepository $repository): bool
    {
        $taxi = $repository->findOneBy(['localizacion' => $localizacion, 'activo' => true]);

        return $taxi !== null;
    }
}
