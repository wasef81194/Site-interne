<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 */
class Etat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=Editeur::class, mappedBy="etat")
     */
    private $editeurs;

    public function __construct()
    {
        $this->editeurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|Editeur[]
     */
    public function getEditeurs(): Collection
    {
        return $this->editeurs;
    }

    public function addEditeur(Editeur $editeur): self
    {
        if (!$this->editeurs->contains($editeur)) {
            $this->editeurs[] = $editeur;
            $editeur->setEtat($this);
        }

        return $this;
    }

    public function removeEditeur(Editeur $editeur): self
    {
        if ($this->editeurs->removeElement($editeur)) {
            // set the owning side to null (unless already changed)
            if ($editeur->getEtat() === $this) {
                $editeur->setEtat(null);
            }
        }

        return $this;
    }
}
