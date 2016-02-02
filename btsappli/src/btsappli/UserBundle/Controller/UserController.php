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
        //on récupère le repository de l'entité fos_user
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        //on récupère tous les étudiants enregistrés en bd
        $tabUser=$repositoryUser->findAll();
        
        //on envoie la listre des étudiants dans la vue chargée de les affichet
        return $this->render('btsappliUserBundle:User:suiviEtudiant.html.twig', array('tabUser'=>$tabUser));
    }
}
