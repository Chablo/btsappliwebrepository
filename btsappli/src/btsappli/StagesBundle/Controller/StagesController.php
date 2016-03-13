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
use Symfony\Component\HttpFoundation\Response;


class StagesController extends Controller
{
    public function voirEntrepriseAction()
    {
        // On récupère le stage de l'utilisateur
        $stage = $this -> getUser() -> getStage();
                                   
        // On récupère l'entreprise 
        $entreprise = $stage->getEntreprise();
        
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliStagesBundle:Stages:vueEntreprise.html.twig',
                             array('entreprise' => $entreprise));
    }
    
    public function voirTuteurAction()
    {
        // On récupère le stage de l'utilisateur
        $stage = $this -> getUser() -> getStage();
                                   
        // On récupère le tuteur
        $tuteur = $stage->getTuteur();
        
        // On transmet le tuteur à la vue chargée de l'afficher
        return $this->render('btsappliStagesBundle:Stages:vueTuteur.html.twig', array('tuteur' => $tuteur));
    }
    
    public function voirStageAction()
    {
        // On récupère le stage de l'utilisateur
        $stage = $this -> getUser() -> getStage();
        
        // On transmet le stage à la vue chargée de l'afficher
        return $this->render('btsappliStagesBundle:Stages:vueStage.html.twig', array('stage' => $stage));
    }

    
    public function ajoutStageEntAction(Request $requeteUtilisateur)
    {
        // On récupère l'utilisateur qui est connecté
        $user = $this -> getUser();
        
        if (empty($user->getStage()) or empty($user->getStage()->getTuteur()))
        {
            if (empty($user->getStage()))
            {
                // Création d'un nouvel objet stage vide
                $stage = new Stage();
            
                // On lie l'objet stage à l'utilisateur
                $user -> setStage($stage);
            }
            else
            {
                $stage=$user->getStage();
            }
            
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
        
                // On redirige vers la page d'affichage de l'entreprise choisie
                return $this->redirect($this->generateUrl('btsappli_stages_voirEntreprise'));
            }
                
            // On appelle la vue chargée d'afficher le formulaire et on lui transmet la représentation graphique du formulaire
            return $this -> render('btsappliStagesBundle:Stages:ajoutStageEnt.html.twig',array (
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
        // On récupère l'objet stage de l'utilisateur
        $stage = $this -> getUser() -> getStage();
        
        if(!empty($stage->getTuteur()))
        {
            // On met le tuteur du stage à null
            $stage -> setTuteur(null);
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            $gestionnaireEntite->persist($stage);
            $gestionnaireEntite->flush();
        }
        
        // On récupère l'entreprise liée au stage
        $entreprise = $stage ->getEntreprise();
        // On récupère l'id de l'entreprise liée au stage pour le formulaire
        $idEntreprise = $entreprise -> getId();
            
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
            // On passe le stage à convention non validée
            $stage -> setEtatConvention(1);
            
            // On enregistre l'objet $user en base de données
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            $gestionnaireEntite->persist($stage);
            $gestionnaireEntite->flush();

             // On redirige vers la page d'affichage du tuteur choisi'
             return $this->redirect($this->generateUrl('btsappli_stages_voirTuteur'));
        }
            
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet la représentation graphique du formulaire
        return $this -> render('btsappliStagesBundle:Stages:ajoutStageTut.html.twig',array(
                                    'formulaireStageTut' => $formulaireStageTut -> createView(),
                                    'entreprise' => $entreprise));
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
             // On redirige vers la page de choix de l'entreprise
             return $this->redirect($this->generateUrl('btsappli_stages_ajoutStageEnt'));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet ma représentation graphique du formulaire
        return $this -> render('btsappliStagesBundle:Stages:ajoutEntreprise.html.twig',
                        array('formulaireEntreprise' => $formulaireEntreprise -> createView()));
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
             return $this->redirect($this->generateUrl('btsappli_stages_ajoutStageTut'));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet la représentation graphique du formulaire
        return $this -> render('btsappliStagesBundle:Stages:ajoutTuteur.html.twig',array(
                                    'formulaireTuteur' => $formulaireTuteur -> createView(),
                                    'entreprise' => $entreprise,
                                    ));
    }
    
    
    public function modificationEntAction(Request $requeteUtilisateur)
    {
        // On récupère l'entreprise choisie précédemment par l'étudiant
        $entreprise = $this->getUser()->getStage()->getEntreprise();
        
        // Création du formulaire permettant de modifier une entreprise
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
             // On redirige vers la page de la vue de l'entreprise choisie et modifiée
             return $this->redirect($this->generateUrl('btsappli_stages_voirEntreprise'));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet la représentation graphique du formulaire
        return $this -> render('btsappliStagesBundle:Stages:modificationEntreprise.html.twig',
                        array('formulaireEntreprise' => $formulaireEntreprise -> createView(),
                              'nomEntreprise' => $entreprise->getNom()));
    }

    public function modificationTutAction(Request $requeteUtilisateur)
    {
        // On récupère le tuteur choisi précédemment par l'étudiant
        $tuteur = $this->getUser()->getStage()->getTuteur();
        
        // Création du formulaire permettant de modifier un tuteur
        $formulaireTuteur = $this->createForm(new TuteurType, $tuteur);
        
        // Enregistrement des données dans $tuteur dès soumission du formulaire
        $formulaireTuteur->handleRequest($requeteUtilisateur);
        
        // Si le formulaire a été soumis et que les données sont valides
        if($formulaireTuteur->isValid())
        {
            // On enregistre l'objet $tuteur en base de données
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            $gestionnaireEntite->persist($tuteur);
            $gestionnaireEntite->flush();
             // On redirige vers la page de la vue du tuteur choisi et modifié
             return $this->redirect($this->generateUrl('btsappli_stages_voirTuteur'));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet la représentation graphique du formulaire
        return $this -> render('btsappliStagesBundle:Stages:modificationTuteur.html.twig',
                        array('formulaireTuteur' => $formulaireTuteur -> createView(),
                              'tuteur' => $tuteur));
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
    
    public function generatePdfAction(Request $request)
    {
       // initialize the $emp variable
        $html = $this->renderView('btsappliStagesBundle:Stages:vueStage.html.twig',
            array('stage'=> $this->getUser()->getStage())
        );

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );
    }

}
?>