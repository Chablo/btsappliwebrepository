<?php

namespace btsappli\CCFBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CCFController extends Controller
{
     public function accueilCCFAction()
    {
        return $this->render('btsappliCCFBundle:CCF:accueilCCF.html.twig');
    }
    
     public function planningCCFAdminAction()
    {
        //on met la liste des étudiants dans tabUserEtCCF afin de la récupérer dans planningCCFAdmin.html.twig
        //on récupère le repository de l'entité CCF
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        //on récupère tous les étudiants enregistrés en bd
        $tabUserEtCCF=$repositoryUser->findAll();
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningCCFAdmin.html.twig', array('tabUserEtCCF'=>$tabUserEtCCF));
    }
    
     public function planningCCFEtuAction($id)
    {
        //on met la liste des étudiants dans tabUserEtCCF afin de la récupérer dans planningCCFAdmin.html.twig
        //on récupère le repository de l'entité CCF
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        //on récupère tous les étudiants enregistrés en bd
        $user=$repositoryUser->find($id);
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningCCFEtu.html.twig', array('user'=>$user));
    }
}
