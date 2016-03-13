<?php

namespace btsappli\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * User
 *
 * @ORM\Entity(repositoryClass="btsappli\UserBundle\Entity\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
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
     * @ORM\Column(name="nom", type="string", length=40)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=40)
     */
    private $prenom;

    /**
     * @var date
     *
     * @ORM\Column(name="dateNaiss", type="date")
     */
    private $dateNaiss;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=200)
     */
    private $adresse;

    /**
     * @var integer
     *
     * @ORM\Column(name="codePostal", type="integer")
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=50)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=14)
     */
    private $telephone;
    
     /**
     * @var boolean
     *
     * @ORM\Column(name="valide", type="boolean")
     */
    private $valide = false;
    
    
    
    
        public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    
    
    //RELATION ENTRE LES CLASSES
    /**
     * 
     * @ORM\ManyToOne(targetEntity="btsappli\StagesBundle\Entity\Stage")
     */
     private $stage; // sans s car un étudiant n'a qu'un seul stage
     
     /**
     * 
     * @ORM\ManyToOne(targetEntity="btsappli\UserBundle\Entity\Promotion")
     */
     private $promotion; // sans s car un étudiant n'appartient qu'à une seule promotion
     
    /**
     *
     * @ORM\ManyToMany(targetEntity="btsappli\CCFBundle\Entity\Ecrit")
     */
    private $ecrits; // avec un s car un étudiant a plusieurs écrits

    
    //GET ET SET
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
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

   
     /**
     * Set dateNaiss
     *
     * @param date $dateNaiss
     * @return User
     */
    public function setDateNaiss($dateNaiss)
    {
        $this->dateNaiss = $dateNaiss;

        return $this;
    }

    /**
     * Get dateNaiss
     *
     * @return date 
     */
    public function getDateNaiss()
    {
        return $this->dateNaiss;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return User
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     * @return User
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return integer 
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return User
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return User
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }


    /**
     * Set stage
     *
     * @param \btsappli\StagesBundle\Entity\Stage $stage
     * @return User
     */
    public function setStage(\btsappli\StagesBundle\Entity\Stage $stage = null)
    {
        $this->stage = $stage;

        return $this;
    }

    /**
     * Get stage
     *
     * @return \btsappli\StagesBundle\Entity\Stage 
     */
    public function getStage()
    {
        return $this->stage;
    }

  
    
        /* Surcharge de fosuser bunble afin que l'identifiant correspondent à l'email */
    public function setEmail($email)
    {
        parent::setEmail($email);
        $this->username = $email;

        return $this;
    }
    public function setEmailCanonical($emailCanonical)
    {
        parent::setEmailCanonical($emailCanonical);
        $this->usernameCanonical = $emailCanonical;

        return $this;
    }
    

    /**
     * Set promotion
     *
     * @param \btsappli\UserBundle\Entity\Promotion $promotion
     * @return User
     */
    public function setPromotion(\btsappli\UserBundle\Entity\Promotion $promotion = null)
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get promotion
     * 
     *
     * @return \btsappli\UserBundle\Entity\Promotion
     */
    public function getPromotion()
    {
        return $this->promotion;
    }
    
    /**
     * Get valide
     *
     * @return boolean 
     */
    public function getValide()
    {
        return $this->valide;
    }

    /**
     * Set valide
     *
     * @param boolean $valide
     * @return User
     */
    public function setValide($valide)
    {
        $this->valide = $valide;

        return $this;
    }

    

    /**
     * Add ecrits
     *
     * @param \btsappli\CCFBundle\Entity\Ecrit $ecrits
     * @return User
     */
    public function addEcrit(\btsappli\CCFBundle\Entity\Ecrit $ecrits)
    {
        $this->ecrits[] = $ecrits;

        return $this;
    }

    /**
     * Remove ecrits
     *
     * @param \btsappli\CCFBundle\Entity\Ecrit $ecrits
     */
    public function removeEcrit(\btsappli\CCFBundle\Entity\Ecrit $ecrits)
    {
        $this->ecrits->removeElement($ecrits);
    }

    /**
     * Get ecrits
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEcrits()
    {
        return $this->ecrits;
    }
}
