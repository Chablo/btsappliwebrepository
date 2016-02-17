<?php
// src/btsappli/StagesBundle/Controller/StagesController.php

namespace btsappli\StagesBundle\Controller;

use StagesBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use btsappli\StagesBundle\Form\Type;
use btsappli\StagesBundle\Entity\Entreprise;
use btsappli\StagesBundle\Entity\EntrepriseRepository;
use btsappli\StagesBundle\Form\EntrepriseType;
use btsappli\StagesBundle\Entity\Tuteur;
use btsappli\StagesBundle\Entity\TuteurRepository;
use btsappli\StagesBundle\Form\TuteurType;
use btsappli\UserBundle\Entity\User;

class StagesController extends Controller
{
    public function menuStageAction()
    {
        $user = $this -> getUser();
        
        return $this->render('btsappliStagesBundle:Stages:menuStage.html.twig',
                            array('id' => $user->getId()));
    }
    
    public function voirEntrepriseAction($id)
    {
        // On récupère le repository de l'entité Entreprise
        $repositoryEntreprise = $this->getDoctrine()->getManager()
                                   -> getRepository('btsappliStagesBundle:Entreprise');
                                   
        // On récupère l'entreprise dont l'id est le paramètre $id
        $entreprise = $repositoryEntreprise->find($id);
        
        // On construit le formulaire qui présentera les données de l'entreprise $id
        $formulaireEntreprise = $this -> createForm(new EntrepriseType, $entreprise, array('read_only' => true));
    
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliStagesBundle:Stages:vueEntreprise.html.twig', 
                             array('formulaireEntreprise' => $formulaireEntreprise -> createView()));
    }
    
    public function voirStageAction($id)
    {
        // On récupère le repository de l'entité User
        $repositoryEntreprise = $this->getDoctrine()->getManager()
                                   -> getRepository('btsappliUserBundle:User');
                                   
        // On récupère l'user dont l'id est le paramètre $id
        $user = $repositoryEntreprise->find($id);        
    
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliStagesBundle:Stages:vueStage.html.twig', 
                             array('user' => $user));
    }
    
    public function rechercherEntrepriseAction(Request $requeteUtilisateur)
    {
        // On construit un tableau pour receuillier les données de la recherche
        $entreprise = new Entreprise(); 

        // Créations du formulaire de recherche
        $formulaireRecherche = $this->createFormBuilder($entreprise)
            ->add('nom', 'text')
            ->getForm();
            
        // Enregistrement des données dans $tabDonneesEntreprise après soumission       
        $formulaireRecherche->handleRequest($requeteUtilisateur);
    
        if ( $formulaireRecherche->isSubmitted() )
        {
            // On redirige vers la page de visualisation des résultats de la recherche
            return $this->redirect($this->generateUrl('btsappli_stages_resultatsRecherche', array('nom' => $entreprise->getNom())));
        }
        
        // À ce stade le visiteur arrive sur la page qui doit présenter le formulaire 
        return $this->render('btsappliStagesBundle:Stages:rechercheEntreprise.html.twig',
               array('formulaireRecherche' => $formulaireRecherche->createView()));
    }
    
    public function resultatsRechercheAction($nom)
    {
        // On récupère le repository de l'entité Entreprise
        $repositoryEntreprise = $this->getDoctrine()->getManager()
                                   -> getRepository('btsappliStagesBundle:Entreprise');
    
        // On récupère la liste des entreprises
        $entreprises = $repositoryEntreprise->getResultatsRechercheEntreprise($nom);
    
        // On transmet la liste des entreprises à la vue chargée de les afficher
        return $this->render('btsappliStagesBundle:Stages:resultatsRecherche.html.twig', 
                             array('entreprises' => $entreprises));
    }   
    
    public function ajoutEntrepriseAction(Request $requeteUtilisateur)
    {
        // On créé un objet Entreprise "vide"
        $entreprise = new Entreprise();
        
        // Création du formulaire permettant de saisir une entreprise
        $formulaireEntreprise = $this->createForm(new EntrepriseType, $entreprise);
        
        // Enregistrement des données dans $entreprise dès soumission du formulaire
        $formulaireEntreprise->handleRequest($requeteUtilisateur);
        
        // Si le formulaire a été soumis et que les données sont valides
        if($formulaireEntreprise->isValid())
        {
            // On enregistre l'objet $entreprise en base de données
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            $gestionnaireEntite->persist($entreprise);
            $gestionnaireEntite->flush();

             // On redirige vers la page de visualisation de l'entreprise ajoutée
             return $this->redirect($this->generateUrl('btsappli_stages_stageFormulaireEnt'));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet ma représentation graphique du formulaire
        return $this -> render('btsappliStagesBundle:Stages:ajoutEntreprise.html.twig',
                        array('formulaireEntreprise' => $formulaireEntreprise -> createView()));
    }
    
    public function ajoutTuteurAction($id, Request $requeteUtilisateur)
    {
        $user = $this -> getUser();
        
        // On créé un objet Tuteur "vide"
        $tuteur = new Tuteur();
        
        // On récupère le repository de l'entité Entreprise
        $repositoryEntreprise = $this->getDoctrine()->getManager()
                                     -> getRepository('btsappliStagesBundle:Entreprise');
        // On récupère l'entreprise dont l'id est le paramètre $id
        $entreprise = $repositoryEntreprise->find($id);  
        
        // On associe à l'objet tuteur l'entreprise dont l'id est $id
        $tuteur -> setEntreprise($entreprise);
        
        // Création du formulaire permettant de saisir une entreprise
        $formulaireTuteur = $this->createForm(new TuteurType, $tuteur);
        
        // Enregistrement des données dans $tuteur dès soumission du formulaire
        $formulaireTuteur->handleRequest($requeteUtilisateur);
        
        // Si le formulaire a été soumis et que les données sont valides
        if($formulaireTuteur->isValid())
        {
            // On enregistre l'objet $entreprise en base de données
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            $gestionnaireEntite->persist($tuteur);
            $gestionnaireEntite->flush();

             // On redirige vers la page du choix du tuteur
             return $this->redirect($this->generateUrl('btsappli_stages_stageFormulaireTut',  array('id' => $user->getEntreprise()->getId())));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet ma représentation graphique du formulaire
        return $this -> render('btsappliStagesBundle:Stages:ajoutTuteur.html.twig',
                        array('formulaireTuteur' => $formulaireTuteur -> createView(),
                              'tuteur' => $tuteur,
                              'id' => $user->getId()));
    }
    
    public function ajoutStageEntAction(Request $requeteUtilisateur)
    {
        $user = $this -> getUser();
        
        // Création du formulaire permettant de choisir son entreprise
        $formulaireStage = $this->createFormBuilder($user)
            ->add('entreprise', 'entity',
                array('label' => 'Choix de l\'entreprise',
                      'class' => 'btsappliStagesBundle:Entreprise',
                      'query_builder' => function (EntrepriseRepository $er) {
                            return $er->createQueryBuilder('e')
                                            ->orderBy('e.nom', 'ASC');
                       },
                      'choice_label' => 'nom',
                      'multiple' => false,
                      'expanded' => false))
            ->getForm();
        
        // Enregistrement des données dans $user dès soumission du formulaire
        $formulaireStage->handleRequest($requeteUtilisateur);
        
        // Si le formulaire a été soumis et que les données sont valides
        if($formulaireStage->isValid())
        {
            // On enregistre l'objet $user en base de données
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            $gestionnaireEntite->persist($user);
            $gestionnaireEntite->flush();

             // On redirige vers la page de visualisation de l'entreprise ajoutée
             return $this->redirect($this->generateUrl('btsappli_stages_stageFormulaireTut', array('id' => $user->getEntreprise()->getId())));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet ma représentation graphique du formulaire
        return $this -> render('btsappliStagesBundle:Stages:ajoutStageEnt.html.twig',
                        array('formulaireStage' => $formulaireStage -> createView()));
    }
    
    public function ajoutStageTutAction($id, Request $requeteUtilisateur)
    {
        $user = $this -> getUser();
        
        // Création du formulaire permettant de choisir son entreprise
        $formulaireStage = $this->createFormBuilder($user)
            ->add('tuteur', 'entity',
                array('label' => 'Choix du tuteur',
                      'class' => 'btsappliStagesBundle:Tuteur',
                      'property' => 'nom',
                      'query_builder' => function(TuteurRepository $tr) use ($id)
                           {
                            return $tr->getTuteursDUneEntreprise($id);
                           },
                      'multiple' => false,
                      'expanded' => false))
            ->getForm();
        
        // Enregistrement des données dans $user dès soumission du formulaire
        $formulaireStage->handleRequest($requeteUtilisateur);
        
        // Si le formulaire a été soumis et que les données sont valides
        if($formulaireStage->isValid())
        {
            // On enregistre l'objet $user en base de données
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            $gestionnaireEntite->persist($user);
            $gestionnaireEntite->flush();

             // On redirige vers la page de récapitulation du stage
             return $this->redirect($this->generateUrl('btsappli_stages_voirStage', array('id' => $user->getId())));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet ma représentation graphique du formulaire
        return $this -> render('btsappliStagesBundle:Stages:ajoutStageTut.html.twig',
                        array('formulaireStage' => $formulaireStage -> createView(),
                              'id' => $user->getEntreprise()->getId(),
                              'tuteur' => $user -> getTuteur()));
    }
    

}

?>