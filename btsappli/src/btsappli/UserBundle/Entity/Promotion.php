<?php

namespace btsappli\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Promotion
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="btsappli\UserBundle\Entity\PromotionRepository")
 */
class Promotion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="anneePromo", type="integer")
     */
    private $anneePromo;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="enCours", type="boolean")
     */
    private $enCours;

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
     * Get anneePromo
     *
     * @return integer
     */
    public function getanneePromo()
    {
        return $this->anneePromo;
    }
    
    /**
     * Set anneePromo
     *
     * @param integer $anneePromo
     * @return Entreprise
     */
    public function setanneePromo($anneePromo)
    {
        $this->anneePromo = $anneePromo;

        return $this;
    }
    
    /**
     * Get enCours
     *
     * @return boolean 
     */
    public function getEnCours()
    {
        return $this->enCours;
    }

    /**
     * Set enCours
     *
     * @param boolean $valide
     * @return User
     */
    public function setEnCours($enCours)
    {
        $this->enCours = $enCours;

        return $this;
    }
}