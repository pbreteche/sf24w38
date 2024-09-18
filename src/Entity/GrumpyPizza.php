<?php

namespace App\Entity;

use App\Repository\GrumpyPizzaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute as Serialization;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GrumpyPizzaRepository::class)]
class GrumpyPizza
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $size = null;

    /**
     * @var Collection<int, Ingredient>
     */
    #[ORM\ManyToMany(targetEntity: Ingredient::class, fetch: 'EAGER')]
    private Collection $composedWith;

    public function __construct()
    {
        $this->composedWith = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): static
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getComposedWith(): Collection
    {
        return $this->composedWith;
    }

    public function addComposedWith(Ingredient $composedWith): static
    {
        if (!$this->composedWith->contains($composedWith)) {
            $this->composedWith->add($composedWith);
        }

        return $this;
    }

    public function removeComposedWith(Ingredient $composedWith): static
    {
        $this->composedWith->removeElement($composedWith);

        return $this;
    }

    #[Assert\Length(max: 128)]
    #[Serialization\Ignore]
    public function getConcatString(): string
    {
        return $this->name;
    }
}
