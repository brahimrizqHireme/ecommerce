<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\MetadataInterface;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{

    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    private $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'product.name.not_blank', groups: ['group'])]
    #[Assert\Length(min: 8, max: 50, groups: ['group'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    #[Assert\PositiveOrZero(groups: ['group'])]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[Assert\NotBlank()]
    private ?Category $category = null;

    #[ORM\Column(length: 255)]
    #[Assert\Url()]
    #[Assert\NotBlank()]
    private ?string $mainPicture = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min: 10, max: 1000)]
    private ?string $shortDescription = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    // public static function loadValidatorMetadata(ClassMetadata $metadata)
    // {
    //     $metadata->addPropertyConstraints('name', [
    //         new Assert\NotBlank(['message' => 'product.name.not_blank']),
    //         new Assert\Length(['min' => 3, 'max' => 50])
    //     ]);
    //     $metadata->addPropertyConstraints('price', [
    //         new Assert\NotBlank(),
    //         new Assert\Positive()
    //     ]);
    // }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    public function setMainPicture(?string $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}
