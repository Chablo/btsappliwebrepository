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
     * @ORM\Column(name="heure", type="time")
     */
    private $heure;

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
     * Set heure
     *
     * @param \DateTime $heure
     * @return Oral
     */
    public function setHeure($heure)
    {
        $this->heure = $heure;

        return $this;
    }

    /**
     * Get heure
     *
     * @return \DateTime 
     */
    public function getHeure()
    {
        return $this->heure;
    }
}
