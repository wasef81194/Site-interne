<?php

namespace App\Entity;

use App\Repository\EditeurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EditeurRepository::class)
 */
class Editeur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="editeurs")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="editeurs")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=Appareil::class, mappedBy="editeur")
     */
    private $appareils;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    public function __construct()
    {
        $this->etats = new ArrayCollection();
        $this->appareils = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection|Appareil[]
     */
    public function getAppareils(): Collection
    {
        return $this->appareils;
    }

    public function addAppareil(Appareil $appareil): self
    {
        if (!$this->appareils->contains($appareil)) {
            $this->appareils[] = $appareil;
            $appareil->setEditeur($this);
        }

        return $this;
    }

    public function removeAppareil(Appareil $appareil): self
    {
        if ($this->appareils->removeElement($appareil)) {
            // set the owning side to null (unless already changed)
            if ($appareil->getEditeur() === $this) {
                $appareil->setEditeur(null);
            }
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
