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

    #[ORM\ManyToOne(targetEntity: Plat::class, inversedBy: 'ingredientsPlats')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Plat $plat;

    #[ORM\ManyToOne(targetEntity: Ingredient::class, inversedBy: 'ingredientsPlats')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Ingredient $ingredient;

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
}