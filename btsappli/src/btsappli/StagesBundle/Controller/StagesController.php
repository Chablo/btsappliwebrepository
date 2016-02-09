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
    public function rechercherEntrepriseAction()
    {
        // On construit un tableau pour receuillier les données de la recherche
        $tabDonneesEntreprise = array(); 

        // Créations du formulaire de contact
        $createurFormulaires = $this->createFormBuilder($tabDonneesEntreprise);

        /* On utilise le constructeur de formulaires pour définir les champs qui 
           constitueront le formulaire de contact */
        $createurFormulaires = $this->createFormBuilder($tabDonneesEntreprise);
            ->add('nomEntreprise', 'text');
            ->getForm();
            
        // Enregistrement des données dans $tabDonneesDuMessage après soumission       
        $formulaireContact->handleRequest($requeteUtilisateur);
         
        /* On envoie la représentation graphique du formulaire à la vue 
           chargée de l'afficher */
        return $this->render('btsappliStagesBundle:Stages:rechercheEntreprise.html.twig',
               array('formulaireRecherche' => $representationGraphiqueFormulaire));
    
    
        if ( $formulaireContact->isSubmitted() )
        {
            /* on récupère les données du formulaire dans un tableau de 2 cases 
               indicées par 'emailExpediteur' et 'contenuMessage' */
            $tabMessage = $formulaireContact->getData ();
    
            /* On traite les données du formulaire (ici on les affiche sur la 
               prochaine page sous forme de réponse brute) */
            return new Response ( "Vous avez reçu un nouveau message de la part 
            de ".$tabMessage['emailExpediteur']."<br /> Voici le contenu du 
            message : <br />".$tabMessage['contenuMessage']);        
        }
        // À ce stade le visiteur arrive sur la page qui doit présenter le formulaire 
        return $this->render('biblinumLivresBundle:Livres:contact.html.twig',
               array('formulaireContact' => $formulaireContact->createView()));
        }
        
    
    public function ajoutEntrepriseAction()
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

?>