<?php

namespace App\Entity;

use App\Repository\PrescriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrescriptionRepository::class)]
class Prescription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $laDate = null;

    #[ORM\OneToMany(mappedBy: 'prescription', targetEntity: Indication::class)]
    private Collection $lesIndications;

    public function __construct()
    {
        $this->lesIndications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLaDate(): ?\DateTimeInterface
    {
        return $this->laDate;
    }

    public function setLaDate(\DateTimeInterface $laDate): static
    {
        $this->laDate = $laDate;

        return $this;
    }

    /**
     * @return Collection<int, Indication>
     */
    public function getLesIndications(): Collection
    {
        return $this->lesIndications;
    }

    public function addLesIndication(Indication $lesIndication): static
    {
        if (!$this->lesIndications->contains($lesIndication)) {
            $this->lesIndications->add($lesIndication);
            $lesIndication->setPrescription($this);
        }

        return $this;
    }

    public function removeLesIndication(Indication $lesIndication): static
    {
        if ($this->lesIndications->removeElement($lesIndication)) {
            // set the owning side to null (unless already changed)
            if ($lesIndication->getPrescription() === $this) {
                $lesIndication->setPrescription(null);
            }
        }

        return $this;
    }

    public function dateToString()
    {
        return $this->getLaDate()->format('Y-m-d'); 
        
    } 

    public function __toString()
    {
        return $this->getId() ."  " . $this->dateToString();
    }
}
