<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDeReservation;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="reservation")
     */
    private $produit;

      /**
   * @CaptchaAssert\ValidCaptcha(
   *      message = "CAPTCHA validation failed, try again."
   * )
   */
  protected $captchaCode;

  public function getCaptchaCode()
  {
    return $this->captchaCode;
  }

  public function setCaptchaCode($captchaCode)
  {
    $this->captchaCode = $captchaCode;
  }
    public function __construct()
    {
        $this->produit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDeReservation(): ?\DateTimeInterface
    {
        return $this->dateDeReservation;
    }

    public function setDateDeReservation(\DateTimeInterface $dateDeReservation): self
    {
        $this->dateDeReservation = $dateDeReservation;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produit->contains($produit)) {
            $this->produit[] = $produit;
            $produit->setReservation($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produit->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getReservation() === $this) {
                $produit->setReservation(null);
            }
        }

        return $this;
    }

    /*public  function _toString(){
        $p=$this->getDateDeReservation();
        $result = $p->format('Y-m-d H:i:s');
        return  '';
    }*/

    public function __toString()
    {
        return (String) $this->getId();
    }
}
