<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Plat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $nomPlat;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $prixUnitaire;

    #[ORM\Column(type: 'time')]
    private \DateTimeInterface $tempsCuisson;

    // #[ORM\OneToMany(targetEntity: IngredientPlat::class, mappedBy: 'plat', cascade: ['persist', 'remove'])]
    private Collection $ingredientsPlats;

    public function __construct()
    {
        $this->ingredientsPlats = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNomPlat(): string
    {
        return $this->nomPlat;
    }

    public function setNomPlat(string $nomPlat): self
    {
        $this->nomPlat = $nomPlat;
        return $this;
    }

    public function getPrixUnitaire(): float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;
        return $this;
    }

    public function getTempsCuisson(): \DateTimeInterface
    {
        return $this->tempsCuisson;
    }

    public function setTempsCuisson(\DateTimeInterface $tempsCuisson): self
    {
        $this->tempsCuisson = $tempsCuisson;
        return $this;
    }

    public function getIngredientsPlats(): Collection
    {
        return $this->ingredientsPlats;
    }

    public function addIngredientPlat(IngredientPlat $ingredientPlat): self
    {
        if (!$this->ingredientsPlats->contains($ingredientPlat)) {
            $this->ingredientsPlats[] = $ingredientPlat;
            $ingredientPlat->setPlat($this);
        }
        return $this;
    }

    public function removeIngredientPlat(IngredientPlat $ingredientPlat): self
    {
        if ($this->ingredientsPlats->removeElement($ingredientPlat)) {
            if ($ingredientPlat->getPlat() === $this) {
                $ingredientPlat->setPlat(null);
            }
        }
        return $this;
    }
}
