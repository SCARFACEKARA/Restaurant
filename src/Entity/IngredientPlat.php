<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class IngredientPlat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    // #[ORM\ManyToOne(targetEntity: Plat::class, inversedBy: 'ingredientsPlats')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Plat $plat;

    // #[ORM\ManyToOne(targetEntity: Ingredient::class, inversedBy: 'ingredientsPlats')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Ingredient $ingredient;

    /**
     * @var Collection<int, Plat>
     */
    // #[ORM\OneToMany(targetEntity: Plat::class, mappedBy: 'ingredientsPlats')]
    private Collection $plats;

    public function __construct()
    {
        $this->plats = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPlat(): Plat
    {
        return $this->plat;
    }

    public function setPlat(Plat $plat): self
    {
        $this->plat = $plat;
        return $this;
    }

    public function getIngredient(): Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(Ingredient $ingredient): self
    {
        $this->ingredient = $ingredient;
        return $this;
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getPlats(): Collection
    {
        return $this->plats;
    }

    public function addPlat(Plat $plat): static
    {
        if (!$this->plats->contains($plat)) {
            $this->plats->add($plat);
            $plat->setIngredientsPlats($this);
        }

        return $this;
    }

    public function removePlat(Plat $plat): static
    {
        if ($this->plats->removeElement($plat)) {
            // set the owning side to null (unless already changed)
            if ($plat->getIngredientsPlats() === $this) {
                $plat->setIngredientsPlats(null);
            }
        }

        return $this;
    }
}
