<?php

namespace App\Entity;

use App\Repository\IndicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IndicationRepository::class)]
class Indication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $posologie = null;

    #[ORM\ManyToOne(inversedBy: 'lesIndications')]
    private ?Prescription $prescription = null;

    #[ORM\OneToMany(mappedBy: 'indication', targetEntity: Medicament::class)]
    private Collection $leMedicament;

    public function __construct()
    {
        $this->leMedicament = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosologie(): ?string
    {
        return $this->posologie;
    }

    public function setPosologie(string $posologie): static
    {
        $this->posologie = $posologie;

        return $this;
    }

    public function getPrescription(): ?Prescription
    {
        return $this->prescription;
    }

    public function setPrescription(?Prescription $prescription): static
    {
        $this->prescription = $prescription;

        return $this;
    }

    /**
     * @return Collection<int, Medicament>
     */
    public function getLeMedicament(): Collection
    {
        return $this->leMedicament;
    }

    public function addLeMedicament(Medicament $leMedicament): static
    {
        if (!$this->leMedicament->contains($leMedicament)) {
            $this->leMedicament->add($leMedicament);
            $leMedicament->setIndication($this);
        }

        return $this;
    }

    public function removeLeMedicament(Medicament $leMedicament): static
    {
        if ($this->leMedicament->removeElement($leMedicament)) {
            // set the owning side to null (unless already changed)
            if ($leMedicament->getIndication() === $this) {
                $leMedicament->setIndication(null);
            }
        }

        return $this;
    }
    
    public function __toString()
    {
        return $this->getId();
    }
}
