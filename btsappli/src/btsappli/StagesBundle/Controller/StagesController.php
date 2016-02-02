<?php
// src/btsappli/StagesBundle/Controller/StagesController.php

namespace btsappli\StagesBundle\Controller;

use StagesBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use btsappli\StagesBundle\Form\Type;
use btsappli\StagesBundle\Entity\Entreprise;

class StagesController extends Controller
{
    public function ajouterEntrepriseAction()
    {
        // On créé un objet Entreprise "vide"
        $entreprise = new Entreprise();
        
        // Création du formulaire permettant de saisir une entreprise
        $formulaireEntreprise = $this->createFormBuilder($entreprise)
            ->add('nom', 'text')
            ->add('representant', 'text')
            ->add('adresse', 'text')
            ->add('codePostal', 'text')
            ->add('ville', 'text')
            ->add('pays', 'text')
            ->add('adresseMail', 'email')
            ->add('telephone', 'number')
            ->add('fax', 'number')
            ->add('description', 'textarea')
            ->add('serviceAccueil', 'text')
            ->getForm();
            
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet ma représentation graphique du formulaire
        return $this -> render('btsappliStagesBundle:Stages:ajoutEntreprise.html.twig',
                        array('formulaireEntreprise' => $formulaireEntreprise -> createView()));
    }
}
