<?php

namespace btsappli\CCFBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use btsappli\CCFBundle\Entity\Ecrit;
use btsappli\CCFBundle\Form\EcritType;

class CCFController extends Controller
{
    public function accueilCCFAction()
    {
        return $this->render('btsappliCCFBundle:CCF:accueilCCF.html.twig');
    }
    
    public function ajoutEcritAction(Request $requeteUtilisateur)
    {
        // On créé un objet Ecrit "vide"
        $ecrit = new Ecrit();
        
        // Création du formulaire permettant de saisir un ecrit
        $formulaireEcrit = $this->createForm(new EcritType, $ecrit);
        
        // Enregistrement des données dans $ecrit dès soumission du formulaire
        $formulaireEcrit->handleRequest($requeteUtilisateur);
        
        // Si le formulaire a été soumis et que les données sont valides
        if($formulaireEcrit->isValid())
        {
            // On enregistre l'objet $ecrit en base de données
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            $gestionnaireEntite->persist($ecrit);
            $gestionnaireEntite->flush();
            
            // On redirige vers la page de planning des CCF
            return $this->redirect($this->generateUrl('btsappli_CCF_planningCCFAdmin'));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet la représentation graphique du formulaire
        return $this->render('btsappliCCFBundle:CCF:ajoutEcrit.html.twig',
                                array('formulaireEcrit' => $formulaireEcrit -> createView()));
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
