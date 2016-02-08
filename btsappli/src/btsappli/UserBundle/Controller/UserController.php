<?php

namespace btsappli\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function accueilEtudiantAction()
    {
        return $this->render('btsappliUserBundle:User:accueilEtudiant.html.twig');
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
   
   
   
        //on met la liste des promotions dans tabPromotion 
        //on récupère le repository de l'entité Promotion
        $repositoryPromotion=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        //on récupère toutes les promotions enregistrés en bd
        $tabPromotion=$repositoryPromotion->findAll();
        
        //on envoie la liste des promotions dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:suiviEtudiant.html.twig', array('tabPromotion'=>$tabPromotion));
    }
}
