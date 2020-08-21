<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InscriptionRepository::class)
 */
class Inscription
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_inscription;

    /**
     * @ORM\ManyToOne(targetEntity=Sorties::class, inversedBy="inscriptions")
     */
    private $sorties_no_sortie;

    /**
     * @ORM\ManyToOne(targetEntity=Participants::class, inversedBy="inscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $participants_no_participant;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->date_inscription;
    }

    public function setDateInscription(\DateTimeInterface $date_inscription): self
    {
        $this->date_inscription = $date_inscription;

        return $this;
    }


    public function getSortiesNoSortie()
    {
        return $this->sorties_no_sortie;
    }

    public function addSortiesNoSortie(Sorties $sortiesNoSortie)
    {

            $this->sorties_no_sortie= $sortiesNoSortie;

    }


    public function getParticipantsNoParticipant(): ?Participants
    {
        return $this->participants_no_participant;
    }

    public function setParticipantsNoParticipant(?Participants $participants_no_participant): self
    {
        $this->participants_no_participant = $participants_no_participant;

        return $this;
    }
}
