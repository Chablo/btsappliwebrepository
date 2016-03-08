<?php

namespace btsappli\StagesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="btsappli\StagesBundle\Entity\StageRepository")
 */
class Stage
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
     * @var date
     *
     * @ORM\Column(name="dateDebut", type="date", length=80)
     */
    private $dateDebut;

    /**
     * @var date
     *
     * @ORM\Column(name="dateFin", type="date", length=80)
     */
    private $dateFin;

    /**
     * @var integer
     *
     * @ORM\Column(name="etatConvention", type="integer", nullable=true)
     */
    private $etatConvention = null;


    //RELATION ENTRE LES CLASSES
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="btsappli\StagesBundle\Entity\Entreprise")
     */
     private $entreprise; // sans s car un stage n'appartient qu'à une seule entreprise
     
    /**
     * 
     * @ORM\ManyToOne(targetEntity="btsappli\StagesBundle\Entity\Tuteur")
     */
     private $tuteur; // sans s car un stage n'a q'un seul tuteur
     
     
     
    //GET ET SET
    
    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }
    
    
 
    /**
     * Set dateDebut
     *
     * @param date $dateDebut
     * @return Stage
     */
    public function setdateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return date 
     */
    public function getdateDebut()
    {
        return $this->dateDebut;
    }
    
    
        /**
     * Set dateFin
     *
     * @param date $dateFin
     * @return Stage
     */
    public function setdateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return date 
     */
    public function getdateFin()
    {
        return $this->dateFin;
    }
    
    
    /**
     * Set etatConvention
     *
     * @param string $etatConvention
     * @return Stage
     */
    public function setetatConvention($etatConvention)
    {
        $this->etatConvention = $etatConvention;

        return $this;
    }

    /**
     * Get etatConvention
     *
     * @return string 
     */
    public function getetatConvention()
    {
        return $this->etatConvention;
    }
    
    
     /**
     * Set tuteur
     *
     * @param \btsappli\StagesBundle\Entity\Tuteur $tuteur
     * @return User
     */
    public function setTuteur(\btsappli\StagesBundle\Entity\Tuteur $tuteur = null)
    {
        $this->tuteur = $tuteur;

        return $this;
    }

    /**
     * Get tuteur
     *
     * @return \btsappli\StagesBundle\Entity\Tuteur 
     */
    public function getTuteur()
    {
        return $this->tuteur;
    }
    
    
        /**
     * Set entreprise
     *
     * @param \btsappli\StagesBundle\Entity\Entreprise $entreprise
     * @return Stage
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
