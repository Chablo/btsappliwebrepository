<?php

namespace btsappli\StagesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Tuteur
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="btsappli\StagesBundle\Entity\TuteurRepository")
 */
class Tuteur
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
     * @ORM\Column(name="nom", type="string", length=40)
     * @Assert\NotBlank(message="Le nom du tuteur doit être spécifié.")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=40)
     * @Assert\NotBlank(message="Le prénom du tuteur doit être spécifié.")
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresseMail", type="string", length=60)
     * @Assert\NotBlank(message="L'adresse mail du tuteur doit être spécifié.")
     * @Assert\Email(message="L'adresse mail spécifiée n'est pas valide.")
     */
    private $adresseMail;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=14)
     * @Assert\NotBlank(message="Le téléphone du tuteur doit être spécifié.")
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="infosComplementaires", type="text", nullable=true)
     */
    private $infosComplementaires;

    /**
     * @var string
     *
     * @ORM\Column(name="fonction", type="string", length=100)
     * @Assert\NotBlank(message="La fonction du tuteur doit être spécifiée.")
     */
    private $fonction;
    
    
     /**
     * 
     * @ORM\ManyToOne(targetEntity="btsappli\StagesBundle\Entity\Entreprise")
     */
     private $entreprise; //un tuteur ne travaille que dans une seule entreprise



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
     * @return Tuteur
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
    
    public function getNomPrenom()
    {
	    return $this->nom.' '.$this->prenom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Tuteur
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
     * Set adresseMail
     *
     * @param string $adresseMail
     * @return Tuteur
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
     * @return Tuteur
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
     * Set infosComplementaires
     *
     * @param string $infosComplementaires
     * @return Tuteur
     */
    public function setInfosComplementaires($infosComplementaires)
    {
        $this->infosComplementaires = $infosComplementaires;

        return $this;
    }

    /**
     * Get infosComplementaires
     *
     * @return string 
     */
    public function getInfosComplementaires()
    {
        return $this->infosComplementaires;
    }

    /**
     * Set fonction
     *
     * @param string $fonction
     * @return Tuteur
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return string 
     */
    public function getFonction()
    {
        return $this->fonction;
    }

    /**
     * Set entreprise
     *
     * @param \btsappli\StagesBundle\Entity\Entreprise $entreprise
     * @return Tuteur
     */
    public function setEntreprise(\btsappli\StagesBundle\Entity\Entreprise $entreprise = null)
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * Get entreprise
     *
     * @return \btsappli\StagesBundle\Entity\Entreprise 
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }
}
