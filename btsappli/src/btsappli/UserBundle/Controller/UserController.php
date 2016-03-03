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
    
    public function suiviEtudiantAction()
    {
        //on met la liste des étudiants dans tabUser afin de la récupérer dans suiviEtudiant.html.twig
        //on récupère le repository de l'entité fos_user
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        //on récupère tous les étudiants enregistrés en bd
        $tabUser=$repositoryUser->findAll();
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:suiviEtudiant.html.twig', array('tabUser'=>$tabUser));
    }
    
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

    
    
    public function suiviStagesConventionValideeAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
    
        // On récupère tous les users dont le stage est validée
        $tabUsersEtStages = $repositoryUser->findByStage.etatConvention('Validée');     
    
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages)); 
    }
    
    
    public function suiviStagesPasStageAction()
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
    
    
    
    public function validerUserAction()
    {
        //on met la liste des étudiants demandant une inscription dans tabUser afin de la récupérer dans validerInscriptions.html.twig
        //on récupère le repository de l'entité fos_user
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        //on récupère tous les étudiants demandant une inscription en bd
        $tabUser=$repositoryUser->findAll();
        
        //on envoie la liste des étudiants demandant une inscription dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:validerInscriptions.html.twig', array('tabUser'=>$tabUser));
    }
}