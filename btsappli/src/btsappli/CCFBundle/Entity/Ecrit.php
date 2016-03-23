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
     * @var \date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;
    
    /**
     * @var \string
     *
     * @ORM\Column(name="matiere", type="string")
     */
    private $matiere;
    
    /**
     * @var \string
     *
     * @ORM\Column(name="salle1", type="string")
     */
    private $salle1;
    
    /**
     * @var \string
     *
     * @ORM\Column(name="salle2", type="string")
     */
    private $salle2;
    
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

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Ecrit
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
     * Set matiere
     *
     * @param string $matiere
     * @return Ecrit
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
     * Set fin
     *
     * @param \DateTime $fin
     * @return Ecrit
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

    /**
     * Set salle1
     *
     * @param string $salle1
     * @return Ecrit
     */
    public function setSalle1($salle1)
    {
        $this->salle1 = $salle1;

        return $this;
    }

    /**
     * Get salle1
     *
     * @return string 
     */
    public function getSalle1()
    {
        return $this->salle1;
    }

    /**
     * Set salle2
     *
     * @param string $salle2
     * @return Ecrit
     */
    public function setSalle2($salle2)
    {
        $this->salle2 = $salle2;

        return $this;
    }

    /**
     * Get salle2
     *
     * @return string 
     */
    public function getSalle2()
    {
        return $this->salle2;
    }
}
