<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Model\AdressDTO;
use App\Model\CompanyDTO;
use App\Model\GpsDTO;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    #[ORM\Id]
    #[ORM\Column(type: Types::BIGINT)]
    private ?string $siren = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $social_raison = null;

    #[ORM\Column(nullable: true)]
    private ?int $adress_num = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress_voie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adress_city = null;

    #[ORM\Column(nullable: true)]
    private ?int $adress_code = null;

    #[ORM\Column(nullable: true)]
    private ?float $gps_latitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $gps_longitude = null;

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(string $siren): static
    {
        $this->siren = $siren;

        return $this;
    }

    public function getSocialRaison(): ?string
    {
        return $this->social_raison;
    }

    public function setSocialRaison(?string $social_raison): static
    {
        $this->social_raison = $social_raison;

        return $this;
    }

    public function getAdressNum(): ?int
    {
        return $this->adress_num;
    }

    public function setAdressNum(?int $adress_num): static
    {
        $this->adress_num = $adress_num;

        return $this;
    }

    public function getAdressVoie(): ?string
    {
        return $this->adress_voie;
    }

    public function setAdressVoie(?string $adress_voie): static
    {
        $this->adress_voie = $adress_voie;

        return $this;
    }

    public function getAdressCode(): ?string
    {
        return $this->adress_code;
    }

    public function setAdressCode(?string $adress_code): static
    {
        $this->adress_code = $adress_code;

        return $this;
    }

    public function getAdressCity(): ?string
    {
        return $this->adress_city;
    }

    public function setAdressCity(?string $adress_city): static
    {
        $this->adress_city = $adress_city;

        return $this;
    }

    public function getGpsLatitude(): ?float
    {
        return $this->gps_latitude;
    }

    public function setGpsLatitude(?float $gps_latitude): static
    {
        $this->gps_latitude = $gps_latitude;

        return $this;
    }

    public function getGpsLongitude(): ?float
    {
        return $this->gps_longitude;
    }

    public function setGpsLongitude(?float $gps_longitude): static
    {
        $this->gps_longitude = $gps_longitude;

        return $this;
    }

    
    public function convertTo(): CompanyDTO {
        $gps = new GpsDTO($this->getGpsLatitude(), $this->getGpsLongitude());
        $adress = new AdressDTO($this->getAdressNum(), $this->getAdressVoie(), $this->getAdressCity(), $this->getAdressCode(), $gps);
        return new CompanyDTO($this->getSiren(), $this->getSocialRaison(), $adress);            
    }

    public static function createFrom(CompanyDTO $dto): Company {
        $adress = $dto->getAdress();
        $gps = $adress->getGps();
        $company = new Company();
        $company->setSiren($dto->getSiren())
        ->setSocialRaison($dto->getSocialRaison())
        ->setAdressNum($adress->getNumero())
        ->setAdressVoie($adress->getVoie())
        ->setAdressCode($adress->getCodePostal())
        ->setAdressCity($adress->getCity())
        ->setGpsLatitude($gps->getLatitude())
        ->setGpsLongitude($gps->getLongitude());

        return $company;
    }
}
