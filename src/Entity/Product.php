<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Ce champs est obligatoire')]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotBlank(message: 'Ce champs est obligatoire')]
    #[ORM\Column(length: 255)]
    private ?string $picture_src = null;

    // propriété utilisée pour le formulaire de modification de produit
    public ?string $update_picture_src = null;

    #[Assert\NotBlank(message: 'Ce champs est obligatoire')]
    #[ORM\Column(length: 255)]
    private ?string $picture_name = null;

    #[Assert\Positive(message: 'Le prix doit être supérieur à 0')]
    #[Assert\NotBlank(message: 'Ce champs est obligatoire')]
    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?bool $disponibility = null;

    #[Assert\NotBlank(message: 'Ce champs est obligatoire')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPictureSrc(): ?string
    {
        return $this->picture_src;
    }

    public function setPictureSrc(string $picture_src): static
    {
        $this->picture_src = $picture_src;

        return $this;
    }

    public function getPictureName(): ?string
    {
        return $this->picture_name;
    }

    public function setPictureName(string $picture_name): static
    {
        $this->picture_name = $picture_name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isDisponibility(): ?bool
    {
        return $this->disponibility;
    }

    public function setDisponibility(bool $disponibility): static
    {
        $this->disponibility = $disponibility;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }
}
