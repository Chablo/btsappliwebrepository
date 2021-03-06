<?php

namespace btsappli\StagesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Entreprise
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="btsappli\StagesBundle\Entity\EntrepriseRepository")
 */
class Entreprise
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=80)
     * @Assert\NotBlank(message="Le nom de l'entreprise doit être spécifié.")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="representant", type="string", length=80)
     * @Assert\NotBlank(message="Le représentant de l'entreprise doit être spécifié.")
     */
    private $representant;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=100)
     * @Assert\NotBlank(message="L'adresse' de l'entreprise doit être spécifiée.")
     */
    private $adresse;

    /**
     * @var integer
     *
     * @ORM\Column(name="codePostal", type="integer")
     * @Assert\NotBlank(message="Le code postal de l'entreprise doit être spécifié.")
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=50)
     * @Assert\NotBlank(message="La ville de l'entreprise doit être spécifiée.")
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=30)
     * @Assert\NotBlank(message="Le pays de l'entreprise doit être spécifié.")
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="adresseMail", type="string", length=100)
     * @Assert\NotBlank(message="L'adresse mail de l'entreprise doit être spécifiée.")
     * @Assert\Email(message="L'adresse mail spécifiée n'est pas valide.")
     */
    private $adresseMail;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=14)
     * @Assert\NotBlank(message="Le téléphone de l'entreprise doit être spécifié.")
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=14, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="serviceAccueil", type="string", length=100)
     * @Assert\NotBlank(message="Le service d'accueil de l'entreprise doit être spécifié.")
     */
    private $serviceAccueil;


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
     * @return Entreprise
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
    
    public function getNomVille()
    {
	    return $this->nom.' - '.$this->ville;
    }

    /**
     * Set representant
     *
     * @param string $representant
     * @return Entreprise
     */
    public function setRepresentant($representant)
    {
        $this->representant = $representant;

        return $this;
    }

    /**
     * Get representant
     *
     * @return string 
     */
    public function getRepresentant()
    {
        return $this->representant;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Entreprise
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
     * @return Entreprise
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
     * @return Entreprise
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
     * Set pays
     *
     * @param string $pays
     * @return Entreprise
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string 
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set adresseMail
     *
     * @param string $adresseMail
     * @return Entreprise
     */
    public function setAdresseMail($adresseMail)
    {
        $this->adresseMail = $adresseMail;

        return $this;
    }

    /**
     * Get adresseMail
     *
     * @return string 
     */
    public function getAdresseMail()
    {
        return $this->adresseMail;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Entreprise
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
     * Set fax
     *
     * @param string $fax
     * @return Entreprise
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Entreprise
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set serviceAccueil
     *
     * @param string $serviceAccueil
     * @return Entreprise
     */
    public function setServiceAccueil($serviceAccueil)
    {
        $this->serviceAccueil = $serviceAccueil;

        return $this;
    }

    /**
     * Get serviceAccueil
     *
     * @return string 
     */
    public function getServiceAccueil()
    {
        return $this->serviceAccueil;
    }
}
