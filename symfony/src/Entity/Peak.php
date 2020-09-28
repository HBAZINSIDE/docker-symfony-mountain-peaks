<?php

namespace App\Entity;

use App\Repository\PeakRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PeakRepository::class)
 */
class Peak
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull
     * @Assert\LessThanOrEqual(value = 90)
     * @Assert\GreaterThanOrEqual(value= -90)
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull
     * @Assert\LessThanOrEqual(value = 180)
     * @Assert\GreaterThanOrEqual(value= -180)
     */
    private $longitude;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull
     * @Assert\GreaterThan(value= 0)
     */
    private $altitude;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $name;

    /**
     * Peak constructor.
     * @param string|null $name
     * @param float|null $altitude
     * @param float|null $latitude
     * @param float|null $longitude
     */
    public function __construct(?string $name = null, ?float $altitude = null, ?float $latitude = null, ?float $longitude = null)
    {
        $this->setName($name);
        $this->setLatitude($latitude);
        $this->setLongitude($longitude);
        $this->setAltitude($altitude);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAltitude(): ?float
    {
        return $this->altitude;
    }

    public function setAltitude(?float $altitude): self
    {
        $this->altitude = $altitude;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
