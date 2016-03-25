<?php

namespace btsappli\CCFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Oral
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="btsappli\CCFBundle\Entity\OralRepository")
 */
class Oral
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
     * @ORM\Column(name="dateDebut", type="date")
     */
    private $date;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="debut", type="time")
     */
    private $debut;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fin", type="time")
     */
    private $fin;

    /**
     * @var string
     *
     * @ORM\Column(name="matiere", type="string", length=30)
     */
    private $matiere;
    
    /**
     * @var \string
     *
     * @ORM\Column(name="salle", type="string")
     */
    private $salle;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="btsappli\UserBundle\Entity\Promotion")
     */
    private $promotion;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="btsappli\UserBundle\Entity\User")
     */
    private $user; // sans s car un oral concerne un seul étudiant


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
     * Set matiere
     *
     * @param string $matiere
     * @return Oral
     */
    public function setMatiere($matiere)
    {
        $this->matiere = $matiere;

        return $this;
    }

    /**
     * Get matiere
     *
     * @return string 
     */
    public function getMatiere()
    {
        return $this->matiere;
    }


    /**
     * Set promotion
     *
     * @param \btsappli\UserBundle\Entity\Promotion $promotion
     * @return Oral
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

    /**
     * Set salle
     *
     * @param string $salle
     * @return Oral
     */
    public function setSalle($salle)
    {
        $this->salle = $salle;

        return $this;
    }

    /**
     * Get salle
     *
     * @return string 
     */
    public function getSalle()
    {
        return $this->salle;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Oral
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user
     *
     * @param \btsappli\UserBundle\Entity\User $user
     * @return Oral
     */
    public function setUser(\btsappli\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \btsappli\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set debut
     *
     * @param \DateTime $debut
     * @return Oral
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
     * Set fin
     *
     * @param \DateTime $fin
     * @return Oral
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin
     *
     * @return \DateTime 
     */
    public function getFin()
    {
        return $this->fin;
    }
}
