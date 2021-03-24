<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints AS Assert;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     * 
     * @Groups({"api_order_read_one", "api_delivery_point"})
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=DeliveryPoint::class, mappedBy="city", orphanRemoval=true)
     */
    private $deliveryPoints;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->deliveryPoints = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|DeliveryPoint[]
     */
    public function getDeliveryPoints(): Collection
    {
        return $this->deliveryPoints;
    }

    public function addDeliveryPoint(DeliveryPoint $deliveryPoint): self
    {
        if (!$this->deliveryPoints->contains($deliveryPoint)) {
            $this->deliveryPoints[] = $deliveryPoint;
            $deliveryPoint->setCity($this);
        }

        return $this;
    }

    public function removeDeliveryPoint(DeliveryPoint $deliveryPoint): self
    {
        if ($this->deliveryPoints->removeElement($deliveryPoint)) {
            // set the owning side to null (unless already changed)
            if ($deliveryPoint->getCity() === $this) {
                $deliveryPoint->setCity(null);
            }
        }

        return $this;
    }
}
