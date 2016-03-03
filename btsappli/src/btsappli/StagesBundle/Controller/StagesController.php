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
use btsappli\StagesBundle\Entity\Stage;
use btsappli\StagesBundle\Entity\StageRepository;
use btsappli\StagesBundle\Form\TuteurType;
use btsappli\StagesBundle\Form\StageType;
use btsappli\UserBundle\Entity\User;
use btsappli\StagesBundle\Form\EntrepriseRechercheForm;


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
        $repositoryEntreprise = $this->getDoctrine()->getManager() -> getRepository('btsappliStagesBundle:Entreprise');
                                   
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
        // On récupère le repository de l'entité Stage
        $repositoryStage = $this->getDoctrine()->getManager() -> getRepository('btsappliStagesBundle:Stage');
                                   
        // On récupère le stage dont l'id est le paramètre $id
        $stage = $repositoryStage->find($id);
        
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliStagesBundle:Stages:vueStage.html.twig', array('stage' => $stage));
    }
    
    public function rechercherEntrepriseAction(Request $requeteUtilisateur)
    {
        // On récupère le repository de l'entité Entreprise
        $repositoryEntreprise = $this->getDoctrine()->getManager() -> getRepository('btsappliStagesBundle:Entreprise');
                                   
        // On récupère l'entreprise dont l'id est le paramètre $id
        $entreprises = $repositoryEntreprise->findAll();
        
        $formulaireRecherche = $this -> createForm(new EntrepriseRechercheForm());
	
	    return $this->render('btsappliStagesBundle:Stages:rechercheEntreprise.html.twig', 
	                            array('entreprises' => $entreprises,
	                                  'formulaireRecherche' => $formulaireRecherche->createView()));
    }
    
    public function resultatRechercheAction()
    {
        $request = $this->getRequest();
    
        if($request->isXmlHttpRequest())
        {
            $motcle = '';
            $motcle = $request->getRequest('motcle');
            
            // On récupère le gestionnaire d'entité
            $gestionnaireEntite = $this->getDoctrine()->getManager();
    
            if($motcle != '')
            {
                   $qb = $gestionnaireEntite->createQueryBuilder();
    
                   $qb->select('e')
                      ->from('btsappliStagesBundle:Entreprise', 'e')
                      ->where("e.nom LIKE :motcle")
                      ->orderBy('e.nom', 'ASC')
                      ->setParameter('motcle', '%'.$motcle.'%');
    
                   $query = $qb->getQuery();               
                   $entreprises = $query->getResult();
            }
            else {
                $entreprises = $gestionnaireEntite->getRepository('btsappliStagesBundle:Entreprise')->findAll();
            }
    
            return $this->render('btsappliStagesBundle:Entreprise:resultatRecherche.html.twig', array(
                'entreprises' => $entreprises
                ));
        }
        else {
            return $this->rechercheEntrepriseAction();
        }
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
    
    public function ajoutStageAction(Request $requeteUtilisateur)
    {
        // On récupère l'utilisateur qui est connecté
        $user = $this -> getUser();
        
        if (empty($user->getStage()) or empty($user->getStage()->getTuteur()))
        {
            if (!empty($user->getStage()))
            {
                $stage = $user->getStage();
                // On supprime l'objet $stage en base de données
                $gestionnaireEntite = $this->getDoctrine()->getManager();
                $gestionnaireEntite->remove($stage);
                $gestionnaireEntite->flush();
                
            }
            
                // Création d'un nouvel objet stage vide
                $stage = new Stage();
                
                // On lie l'objet stage à l'utilisateur
                $user -> setStage($stage);
                
                // Création du formulaire permettant de saisir un stage
                $formulaireStage = $this -> createForm(new StageType, $stage);
                
                // Enregistrement des données dans $stage dès soumission du formulaire
                $formulaireStage->handleRequest($requeteUtilisateur);
                
                // Si le formulaire a été soumis et que les données sont valides
                if($formulaireStage->isValid())
                {
                    // On enregistre l'objet $stage en base de données
                    $gestionnaireEntite = $this->getDoctrine()->getManager();
                    $gestionnaireEntite->persist($stage);
                    $gestionnaireEntite->flush();
        
                    // On redirige vers la page du choix du tuteur
                    return $this->redirect($this->generateUrl('btsappli_stages_stageFormulaireTut',  array('id' => $stage -> getId())));
                }
                
                // On appelle la vue chargée d'afficher le formulaire et on lui transmet la représentation graphique du formulaire
                return $this -> render('btsappliStagesBundle:Stages:ajoutStage.html.twig',array (
                                                    'formulaireStage' => $formulaireStage -> createView()));
        }
        else
        {
            return $this -> render('btsappliStagesBundle:Stages:erreurStage.html.twig', array(
                                                'id' => $user -> getStage()-> getId()));
        }
    }
    
    public function ajoutStageTutAction(Request $requeteUtilisateur)
    {
        // On récupère l'utilisateur connecté
        $user = $this -> getUser();
        
        // On récupère l'objet stage de l'utilisateur
        $stage = $user -> getStage();
    
        if (empty($stage->getTuteur()))
        {
            // On récupère l'id de l'entreprise liée au stage pour l'envoyer dans la vue
            $idEntreprise = $stage -> getEntreprise() -> getId();
            
            // Création du formulaire permettant de choisir son tuteur
            $formulaireStageTut = $this->createFormBuilder($stage)
                ->add('tuteur', 'entity',array(
                            'label' => 'Choix du tuteur',
                            'class' => 'btsappliStagesBundle:Tuteur',
                            'property' => 'NomPrenom',
                            'query_builder' => function(TuteurRepository $tr) use ($idEntreprise)
                               {
                                return $tr->getTuteursDUneEntreprise($idEntreprise);
                               },
                            'multiple' => false,
                            'expanded' => false))
                ->getForm();
            
            // Enregistrement des données dans $user dès soumission du formulaire
            $formulaireStageTut->handleRequest($requeteUtilisateur);
            
            // Si le formulaire a été soumis et que les données sont valides
            if($formulaireStageTut->isValid())
            {
                // On enregistre l'objet $user en base de données
                $gestionnaireEntite = $this->getDoctrine()->getManager();
                $gestionnaireEntite->persist($stage);
                $gestionnaireEntite->flush();
    
                 // On redirige vers la page de récapitulatif du stage
                 return $this->redirect($this->generateUrl('btsappli_stages_voirStage', array('id' => $stage -> getId())));
            }
            
            // On appelle la vue chargée d'afficher le formulaire et on lui transmet la représentation graphique du formulaire
            return $this -> render('btsappliStagesBundle:Stages:ajoutStageTut.html.twig',array(
                                        'formulaireStageTut' => $formulaireStageTut -> createView(),
                                        'id' => $idEntreprise));
        }
        else
        {
            return $this -> render('btsappliStagesBundle:Stages:erreurStage.html.twig', array(
                                        'id' => $stage-> getId())); 
        }
    }

    public function ajoutTuteurAction($id,  Request $requeteUtilisateur)
    {
        // On récupère l'utilisateur qui est connecté
        $user = $this -> getUser();
        
        // On récupère le stage de l'utilisateur connecté pour pouvoir envoyer à la vue son id
        $stage = $user -> getStage();
        
        // On créé un objet Tuteur "vide"
        $tuteur = new Tuteur();
        
        // On récupère le repository de l'entité Entreprise
        $repositoryEntreprise = $this->getDoctrine()->getManager() -> getRepository('btsappliStagesBundle:Entreprise');
        
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
             return $this->redirect($this->generateUrl('btsappli_stages_stageFormulaireTut',  array(
                                                                        'id' => $stage -> getId(),
                                                                        )));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet la représentation graphique du formulaire
        return $this -> render('btsappliStagesBundle:Stages:ajoutTuteur.html.twig',array(
                                    'formulaireTuteur' => $formulaireTuteur -> createView(),
                                    'entreprise' => $entreprise,
                                    ));
    }

}

?>