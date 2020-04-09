<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 */
class Produit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $latin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeProduit", inversedBy="produits")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProduitRecette", mappedBy="produit", orphanRemoval=true)
     */
    private $produitRecettes;

    public $nomType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Aromatheque", mappedBy="produit")
     */
    private $aromatheques;

    /**
     * @ORM\Column(type="boolean")
     */
    private $moderation;

    public function __construct()
    {
        $this->aromatheques = new ArrayCollection();
        $this->produitRecettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLatin(): ?string
    {
        return $this->latin;
    }

    public function setLatin(string $latin): self
    {
        $this->latin = $latin;

        return $this;
    }

    public function getType(): ?TypeProduit
    {
        return $this->type;
    }

    public function setType(?TypeProduit $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNomType(){

        return $this->nom . ' (' . $this->type . ')';
    }

    /**
     * @return Collection|ProduitRecette[]
     */
    public function getProduitRecettes(): Collection
    {
        return $this->produitRecettes;
    }

    public function addProduitRecette(ProduitRecette $produitRecette): self
    {
        if (!$this->produitRecettes->contains($produitRecette)) {
            $this->produitRecettes[] = $produitRecette;
            $produitRecette->setProduit($this);
        }

        return $this;
    }

    public function removeProduitRecette(ProduitRecette $produitRecette): self
    {
        if ($this->produitRecettes->contains($produitRecette)) {
            $this->produitRecettes->removeElement($produitRecette);
            // set the owning side to null (unless already changed)
            if ($produitRecette->getProduit() === $this) {
                $produitRecette->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * Compare les produits des aromatèques et des recettes
     * 
     * @return
     */
    public function getRecetteWithAromatheque(){

        // $coucou = [];
        $test = null;

        // foreach($this->aromatheques as $aromatheque){

        //     $coucou = $coucou . $aromatheque ;

        // }

        return $this->$test;

    }

    /**
     * @return Collection|Aromatheque[]
     */
    public function getAromatheques(): Collection
    {
        return $this->aromatheques;
    }

    public function addAromatheque(Aromatheque $aromatheque): self
    {
        if (!$this->aromatheques->contains($aromatheque)) {
            $this->aromatheques[] = $aromatheque;
            $aromatheque->setProduit($this);
        }

        return $this;
    }

    public function removeAromatheque(Aromatheque $aromatheque): self
    {
        if ($this->aromatheques->contains($aromatheque)) {
            $this->aromatheques->removeElement($aromatheque);
            // set the owning side to null (unless already changed)
            if ($aromatheque->getProduit() === $this) {
                $aromatheque->setProduit(null);
            }
        }

        return $this;
    }

    public function getModeration(): ?bool
    {
        return $this->moderation;
    }

    public function setModeration(bool $moderation): self
    {
        $this->moderation = $moderation;

        return $this;
    }
}
