<?php

namespace App\Entity;

use App\Entity\OrderProduct;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints AS Assert;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({"api_order_browse", "api_order_read_one"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups({"api_order_browse", "api_order_read_one"})
     * @Assert\NotBlank
     */
    private $deliveryTime;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Groups({"api_order_browse", "api_order_read_one"})
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups("api_order_read_one")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=OrderProduct::class, mappedBy="relatedToOrder", orphanRemoval=true, cascade={"persist"})
     * 
     * @Groups("api_order_read_one")
     * @Assert\Valid
     */
    private $orderProducts;

    /**
     * @ORM\ManyToOne(targetEntity=DeliveryPoint::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups("api_order_read_one")
     * @Assert\NotBlank
     */
    private $deliveryPoint;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
        $this->status = 0;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliveryTime(): ?\DateTimeInterface
    {
        return $this->deliveryTime;
    }

    public function setDeliveryTime(\DateTimeInterface $deliveryTime): self
    {
        $this->deliveryTime = $deliveryTime;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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
     * @return Collection|OrderProduct[]
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): self
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts[] = $orderProduct;
            $orderProduct->setRelatedToOrder($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): self
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getRelatedToOrder() === $this) {
                $orderProduct->setRelatedToOrder(null);
            }
        }

        return $this;
    }

    public function getDeliveryPoint(): ?DeliveryPoint
    {
        return $this->deliveryPoint;
    }

    public function setDeliveryPoint(?DeliveryPoint $deliveryPoint): self
    {
        $this->deliveryPoint = $deliveryPoint;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
