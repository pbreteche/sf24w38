<?php

namespace App\Entity;

use App\Repository\PizzaExportConfigRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PizzaExportConfigRepository::class)]
class PizzaExportConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, ApiUser>
     */
    #[ORM\OneToMany(targetEntity: ApiUser::class, mappedBy: 'pizzaExportConfig', cascade: ['persist'])]
    private Collection $owners;

    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $fields = [];

    public function __construct()
    {
        $this->owners = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, ApiUser>
     */
    public function getOwners(): Collection
    {
        return $this->owners;
    }

    public function addOwner(ApiUser $owner): static
    {
        if (!$this->owners->contains($owner)) {
            $this->owners->add($owner);
            $owner->setPizzaExportConfig($this);
        }

        return $this;
    }

    public function removeOwner(ApiUser $owner): static
    {
        if ($this->owners->removeElement($owner)) {
            // set the owning side to null (unless already changed)
            if ($owner->getPizzaExportConfig() === $this) {
                $owner->setPizzaExportConfig(null);
            }
        }

        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function addField(string $field): static
    {
        if (!in_array($field, $this->fields)) {
            $this->fields[] = $field;
        }

        return $this;
    }

    public function removeField(string $field): static
    {
        $key = array_search($field, $this->fields);
        if (false !== $key) {
            unset($this->fields[$key]);
        }

        return $this;
    }

    public function updateOwners(): void
    {
        foreach ($this->owners as $owner) {
            $owner->setPizzaExportConfig($this);
        }
    }
}
