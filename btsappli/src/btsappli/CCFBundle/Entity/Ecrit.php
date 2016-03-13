<?php

namespace btsappli\CCFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ecrit
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="btsappli\CCFBundle\Entity\EcritRepository")
 */
class Ecrit
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="duree", type="time")
     */
    private $duree;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="debut", type="time")
     */
    private $debut;

    /**
     *
     * @ORM\ManyToOne(targetEntity="btsappli\CCFBundle\Entity\Salle")
     */
    private $salle;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="btsappli\UserBundle\Entity\Promotion")
     */
    private $promotion;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set duree
     *
     * @param \DateTime $duree
     * @return Ecrit
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return \DateTime 
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set debut
     *
     * @param \DateTime $debut
     * @return Ecrit
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;

        return $this;
    }

    /**
     * Get debut
     *
     * @return \DateTime 
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * Set salle
     *
     * @param \btsappli\CCFBundle\Entity\Salle $salle
     * @return Ecrit
     */
    public function setSalle(\btsappli\CCFBundle\Entity\Salle $salle = null)
    {
        $this->salle = $salle;

        return $this;
    }

    /**
     * Get salle
     *
     * @return \btsappli\CCFBundle\Entity\Salle 
     */
    public function getSalle()
    {
        return $this->salle;
    }

    /**
     * Set promotion
     *
     * @param \btsappli\UserBundle\Entity\Promotion $promotion
     * @return Ecrit
     */
    public function setPromotion(\btsappli\UserBundle\Entity\Promotion $promotion = null)
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get promotion
     *
     * @return \btsappli\UserBundle\Entity\Promotion 
     */
    public function getPromotion()
    {
        return $this->promotion;
    }
}
