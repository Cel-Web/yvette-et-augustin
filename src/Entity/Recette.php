<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use App\Entity\ProduitRecette;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecetteRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Recette
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
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="recettes")
     */
    private $categorie;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recettes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publiePar;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProduitRecette", mappedBy="recette", cascade="remove")
     */
    private $produitRecettes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaire", mappedBy="recette", cascade="remove")
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Favoris", mappedBy="recette")
     */
    private $favoris;

    /**
     * @ORM\Column(type="boolean")
     */
    private $moderation;

    public function __construct()
    {
        $this->produitRecettes = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->favoris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPubliePar(): ?User
    {
        return $this->publiePar;
    }

    public function setPubliePar(?User $publiePar): self
    {
        $this->publiePar = $publiePar;

        return $this;
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
            $produitRecette->setRecette($this);
        }

        return $this;
    }

    public function removeProduitRecette(ProduitRecette $produitRecette): self
    {
        if ($this->produitRecettes->contains($produitRecette)) {
            $this->produitRecettes->removeElement($produitRecette);
            // set the owning side to null (unless already changed)
            if ($produitRecette->getRecette() === $this) {
                $produitRecette->setRecette(null);
            }
        }

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

    /**
     * Permet d'initialiser le slug
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function initializeSlug(){
        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->titre);
        }
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setRecette($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getRecette() === $this) {
                $commentaire->setRecette(null);
            }
        }

        return $this;
    }

    /**
     * Permet d'obtenir la moyenne globale des notes pour cette recette
     *
     * @return float
     */
    public function getAvgRatings(){

        $sum = array_reduce($this->commentaires->toArray(), function ($total, $commentaires){
            return $total + $commentaires->getNote();
        }, 0);

        if(count($this->commentaires) >0) return $moyenne = $sum / count($this->commentaires);
        
        return 0;
    }

    /**
     * @return Collection|Favoris[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
            $favori->setRecette($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): self
    {
        if ($this->favoris->contains($favori)) {
            $this->favoris->removeElement($favori);
            // set the owning side to null (unless already changed)
            if ($favori->getRecette() === $this) {
                $favori->setRecette(null);
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