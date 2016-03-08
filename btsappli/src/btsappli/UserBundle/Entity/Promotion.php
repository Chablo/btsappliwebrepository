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
     * @var string
     *
     * @ORM\Column(name="anneePromo", type="string", length=20)
     */
    private $anneePromo;

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
     * @return string 
     */
    public function getanneePromo()
    {
        return $this->anneePromo;
    }
    
    /**
     * Set anneePromo
     *
     * @param string $anneePromo
     * @return Entreprise
     */
    public function setanneePromo($anneePromo)
    {
        $this->anneePromo = $anneePromo;

        return $this;
    }
}