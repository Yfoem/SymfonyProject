<?php

namespace App\Entity;

use App\Repository\SortiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=SortiesRepository::class)
 */
class Sorties
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
     * @ORM\Column(type="datetime")
     */
    private $datedebut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecloture;

    /**
     * @ORM\Column(type="integer")

     */
    private $nbinscriptionsmax;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $descriptioninfos;



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlPhoto;

    /**
     * @ORM\Column(type="integer")
     */
    private $organisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Lieux::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieux_id;

    /**
     * @ORM\ManyToOne(targetEntity=Etats::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etats_id;

    /**
     * @ORM\oneToMany(targetEntity=Inscription::class, mappedBy="sorties_no_sortie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $inscriptions;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
        $this->creation = new \DateTime("now");
        $this->valid = (new \DateTime("now"))->modify('+1 day');
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

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDatecloture(): ?\DateTimeInterface
    {
        return $this->datecloture;
    }

    public function setDatecloture(\DateTimeInterface $datecloture): self
    {
        $this->datecloture = $datecloture;

        return $this;
    }

    public function getNbinscriptionsmax(): ?int
    {
        return $this->nbinscriptionsmax;
    }

    public function setNbinscriptionsmax(int $nbinscriptionsmax): self
    {
        $this->nbinscriptionsmax = $nbinscriptionsmax;

        return $this;
    }

    public function getDescriptioninfos(): ?string
    {
        return $this->descriptioninfos;
    }

    public function setDescriptioninfos(?string $descriptioninfos): self
    {
        $this->descriptioninfos = $descriptioninfos;

        return $this;
    }


    public function getUrlPhoto(): ?string
    {
        return $this->urlPhoto;
    }

    public function setUrlPhoto(?string $urlPhoto): self
    {
        $this->urlPhoto = $urlPhoto;

        return $this;
    }

    public function getOrganisateur(): ?int
    {
        return $this->organisateur;
    }

    public function setOrganisateur(int $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getLieuxId(): ?Lieux
    {
        return $this->lieux_id;
    }

    public function setLieuxId(?Lieux $lieux_id): self
    {
        $this->lieux_id = $lieux_id;

        return $this;
    }

    public function getEtatsId(): ?Etats
    {
        return $this->etats_id;
    }

    public function setEtatsId(?Etats $etats_id)
    {
        $this->etats_id = $etats_id;

        return $this;
    }

    /**
     * @return Collection|Inscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->addSortiesNoSortie($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->contains($inscription)) {
            $this->inscriptions->removeElement($inscription);
            $inscription->removeSortiesNoSortie($this);
        }

        return $this;
    }
}
