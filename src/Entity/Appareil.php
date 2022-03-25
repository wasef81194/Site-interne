<?php

namespace App\Entity;

use App\Repository\AppareilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppareilRepository::class)
 */
class Appareil
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
    private $marque;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $modele;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ns;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mdp;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $prblm;

    /**
     * @ORM\Column(type="boolean")
     */
    private $chargeur;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, inversedBy="appareil", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Editeur::class, inversedBy="appareils")
     */
    private $editeur;

    /**
     * @ORM\OneToMany(targetEntity=Tache::class, mappedBy="appareil")
     */
    private $taches;

    /**
     * @ORM\OneToOne(targetEntity=Prioritaire::class, mappedBy="appareil", cascade={"persist", "remove"})
     */
    private $prioritaire;

    public function __construct()
    {
        $this->taches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(?string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getNs(): ?string
    {
        return $this->ns;
    }

    public function setNs(?string $ns): self
    {
        $this->ns = $ns;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(?string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getPrblm(): ?string
    {
        return $this->prblm;
    }

    public function setPrblm(?string $prblm): self
    {
        $this->prblm = $prblm;

        return $this;
    }

    public function getChargeur(): ?bool
    {
        return $this->chargeur;
    }

    public function setChargeur(bool $chargeur): self
    {
        $this->chargeur = $chargeur;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getEditeur(): ?Editeur
    {
        return $this->editeur;
    }

    public function setEditeur(?Editeur $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    /**
     * @return Collection|Tache[]
     */
    public function getTaches(): Collection
    {
        return $this->taches;
    }

    public function addTach(Tache $tach): self
    {
        if (!$this->taches->contains($tach)) {
            $this->taches[] = $tach;
            $tach->setAppareil($this);
        }

        return $this;
    }

    public function removeTach(Tache $tach): self
    {
        if ($this->taches->removeElement($tach)) {
            // set the owning side to null (unless already changed)
            if ($tach->getAppareil() === $this) {
                $tach->setAppareil(null);
            }
        }

        return $this;
    }

    public function getPrioritaire(): ?Prioritaire
    {
        return $this->prioritaire;
    }

    public function setPrioritaire(?Prioritaire $prioritaire): self
    {
        // unset the owning side of the relation if necessary
        if ($prioritaire === null && $this->prioritaire !== null) {
            $this->prioritaire->setAppareil(null);
        }

        // set the owning side of the relation if necessary
        if ($prioritaire !== null && $prioritaire->getAppareil() !== $this) {
            $prioritaire->setAppareil($this);
        }

        $this->prioritaire = $prioritaire;

        return $this;
    }
}
