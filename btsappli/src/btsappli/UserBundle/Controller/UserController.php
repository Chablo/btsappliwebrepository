<?php
namespace btsappli\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function accueilEtudiantAction()
    {
        $user = $this -> getUser();
        
        return $this->render('btsappliUserBundle:User:accueilEtudiant.html.twig',
                            array('user' => $user,
                                  'id' => $user -> getId()));
    }
    
    public function accueilAdminAction()
    {
        return $this->render('btsappliUserBundle:User:accueilAdmin.html.twig');
    }
    
// ************* Suivi Etudiant ***********************************************************************************

    public function suiviEtudiantAction()
    {
        //on met la liste des étudiants dans tabUser afin de la récupérer dans suiviEtudiant.html.twig
        //on récupère le repository de l'entité fos_user
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        // on récupère tous les étudiants enregistrés en bd avec leur promo
        $tabUser=$repositoryUser->getUsersEtPromo();
        
        //on récupère le repository de l'entité promo
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // on récupère toutes les promos
        $tabPromotions = $repositoryPromotion -> findAll();
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:suiviEtudiant.html.twig', array(
                                            'tabUser'=>$tabUser,
                                            'tabPromotions'=>$tabPromotions));
    }
    
    public function suiviEtudiantFiltrerAction($idPromo)
    {
        //on met la liste des étudiants dans tabUser afin de la récupérer dans suiviEtudiant.html.twig
        //on récupère le repository de l'entité fos_user
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        //on récupère le repository de l'entité promo
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // on récupère tous les étudiants enregistrés en bd en fonction de leur promo
        $tabUser=$repositoryUser->findByPromotion($idPromo);
        
        // on récupère toutes les promos
        $tabPromotions = $repositoryPromotion -> findAll();
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:suiviEtudiant.html.twig', array(
                                            'tabUser'=>$tabUser,
                                            'tabPromotions'=>$tabPromotions));
    }
    
    public function setPromoAction($id, $idPromo)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
        
        // On récupère l'user dont l'id est le paramètre $id
        $user = $repositoryUser->find($id);
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $gestionnaireEntite->getRepository('btsappliUserBundle:Promotion');
        
        $promo = $repositoryPromotion -> find($idPromo);
        
        $user->setPromotion($promo); 
        $gestionnaireEntite->persist($user);
        $gestionnaireEntite->flush();
        
        // On récupère tous les users
        $tabUser = $repositoryUser->findAll();
        
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // on récupère toutes les promos
        $tabPromotions = $repositoryPromotion -> findAll();
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:suiviEtudiant.html.twig', array(
                                            'tabUser'=>$tabUser,
                                            'tabPromotions'=>$tabPromotions));
    }
    

// ************* Suivi Stages ***********************************************************************************
    
    public function suiviStagesAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
    
        // On récupère tous les users avec leur stage
        $tabUsersEtStages = $repositoryUser->getUsersEtStages();
    
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages)); 
    }
    
        public function voirStageEtuAction()
    {
        // On récupère le stage de l'utilisateur
        $stage = $this -> getUser() -> getStage();
        
        // On transmet le stage à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:vueStage.html.twig', array('stage' => $stage));
    }
    
    public function suiviStagesFiltrerPromoAction($idPromo)
    {
        //on met la liste des étudiants dans tabUser afin de la récupérer dans suiviEtudiant.html.twig
        //on récupère le repository de l'entité fos_user
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        // on récupère tous les étudiants dont la promo est l'id $idPromo
        $tabUsersEtStages=$repositoryUser->getUsersEtStages();
        
        //on récupère le repository de l'entité promo
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // on récupère toutes les promos
        $tabPromotions = $repositoryPromotion -> findAll();
        
        //on récupère le repository de l'entité stage
        $repositoryStage = $this->getDoctrine()->getManager()->getRepository('btsappliStagesBundle:Stage');
        
        // on récupère toutes stages
        $tabStages = $repositoryStage -> findAll();
        
        $idPromotion = $repositoryPromotion -> find($idPromo);
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', array(
                                            'tabUsersEtStages' => $tabUsersEtStages,
                                            'tabPromotions'=>$tabPromotions,
                                            'idPromo'=>$idPromotion,
                                            'tabStages' => $tabStages));
    }
    
    public function suiviStagesFiltrerConventionValideeAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
    
        // On récupère tous les users dont le stage est validée
        $tabUsersEtStages = $repositoryUser->findByEtatConventionValidee();     
    
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages)); 
    }
    
    public function suiviStagesFiltrerConventionNonValideeAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
    
        // On récupère tous les users dont le stage est validée
        $tabUsersEtStages = $repositoryUser->findByEtatConventionNonValidee();     
    
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages)); 
    }
    
    public function suiviStagesFiltrerConventionSigneeAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
    
        // On récupère tous les users dont le stage est validée
        $tabUsersEtStages = $repositoryUser->findByEtatConventionSignee();     
    
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages)); 
    }
    
    public function suiviStagesFiltrerPasStageAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
    
        // On récupère tous les users qui n'ont pas de stage
        $tabUsersEtStages = $repositoryUser->findByStage(null);     
    
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages)); 
    }
    
    public function supprimerStageAction($id)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
    
        // On récupère l'user dont l'id est le paramètre $id
        $user = $repositoryUser->find($id);
        
        // On récupère le stage de l'user
        $stageASupprimer = $user->getStage();
        
        // On met à NULL le stage de l'user
        $user->setStage(null);
        
        // On supprime le stage récupéré de la BD
        $gestionnaireEntite->remove($stageASupprimer);
        $gestionnaireEntite->flush();
    
        // On récupère tous les users avec leur stage
        $tabUsersEtStages = $repositoryUser->getUsersEtStages();     
        
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages));
    }
    
    public function supprimerStageValidationAction($id)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
    
        // On récupère l'user dont l'id est le paramètre $id
        $user = $repositoryUser->find($id);
        
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStagesSuppressionValidation.html.twig',
                                array('user' => $user));
    }
    
    public function voirStageAction($id)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
    
        // On récupère l'user dont l'id est le paramètre $id
        $user = $repositoryUser->find($id);
        
        // On récupère le stage de l'user
        $stageASupprimer = $user->getStage();
        
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:supprimerStage.html.twig',  array(
                                'stage' => $stageASupprimer,
                                'user' => $user));
                             
    }    
    
// *********** Gérer inscriptions *************************************************************************************
    
    public function validationsUserAction()
    {
        //on met la liste des étudiants demandant une inscription dans tabUser afin de la récupérer dans validerInscriptions.html.twig
        //on récupère le repository de l'entité fos_user
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        //on récupère tous les étudiants demandant une inscription en bd
        $tabUser=$repositoryUser->findAll();
        
        //on envoie la liste des étudiants demandant une inscription dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:validerInscriptions.html.twig', array('tabUser'=>$tabUser));
    }
    
    public function supprimerValidationAction($id)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
        
        // On récupère l'user dont l'id est le paramètre id
        $user = $repositoryUser->find($id);

        // On supprime l'user
        $gestionnaireEntite->remove($user);
        $gestionnaireEntite->flush();

        // On récupère tous les users avec leur stage
        $tabUser = $repositoryUser->findAll();  
        
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:validerInscriptions.html.twig', array('tabUser'=>$tabUser));
    }

    public function accepterValidationAction($id)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
    
        // On récupère l'user dont l'id est le paramètre $id
        $user = $repositoryUser->find($id);
        
        // On passe à true l'état valide de l'user
        $user->setValide(true); 
        $gestionnaireEntite->persist($user);
        $gestionnaireEntite->flush();

        // On récupère tous les users
        $tabUser = $repositoryUser->findAll();     
        
        // On transmet les user à la vue
        return $this->render('btsappliUserBundle:User:validerInscriptions.html.twig', array('tabUser'=>$tabUser));
    }
}