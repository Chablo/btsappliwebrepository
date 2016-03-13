<?php

namespace btsappli\CCFBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use btsappli\CCFBundle\Entity\Ecrit;
use btsappli\CCFBundle\Form\EcritType;

class CCFController extends Controller
{
    public function editionChoixTypeCCFAction()
    {
        return $this->render('btsappliCCFBundle:CCF:editionChoisirTypeCCF.html.twig');
    }
    
    public function planningChoixTypeCCFAction()
    {
        return $this->render('btsappliCCFBundle:CCF:planningChoisirTypeCCF.html.twig');
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
            
            // On récupère le repository de l'entité User
            $repositoryUser=$gestionnaireEntite->getRepository('btsappliUserBundle:User');
            
            // On récupère la promotion concernée par l'écrit
            $promotion = $ecrit->getPromotion();
            
            // On récupère les User concernés par l'écrit
            $tabUsers = $repositoryUser->findByPromotion($promotion);
            
            // On attribue aux users récupérés l'écrit
            for ($i = 0, $size = count($tabUsers);$i < $size; ++$i)
            {
                $tabUsers[$i] -> addEcrit($ecrit);
                
                // On enregistre en bd
                $gestionnaireEntite->persist($tabUsers[$i]);
                $gestionnaireEntite->flush();
            }
            
            // On redirige vers la page de planning des CCF
            return $this->redirect($this->generateUrl('btsappli_CCF_planningEcrits'));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet la représentation graphique du formulaire
        return $this->render('btsappliCCFBundle:CCF:ajoutEcrit.html.twig',
                                array('formulaireEcrit' => $formulaireEcrit -> createView()));
    }
    
    public function planningOrauxAction()
    {
        //on met la liste des étudiants dans tabUserEtCCF afin de la récupérer dans planningCCFAdmin.html.twig
        //on récupère le repository de l'entité CCF
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        //on récupère tous les étudiants enregistrés en bd
        $tabUserEtCCF=$repositoryUser->findByPromoEnCours();
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningOraux.html.twig', array('tabUserEtCCF'=>$tabUserEtCCF));
    }
    
    public function planningEcritsAction()
    {
        // On récupère le repository de l'entité Ecrit
        $repositoryEcrit=$this->getDoctrine()->getManager()->getRepository('btsappliCCFBundle:Ecrit');
        
        // On récupère tous les écrits
        $tabEcrits = $repositoryEcrit->findAll();
        
        // On envoie la liste des écrits dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningEcrits.html.twig', array('tabEcrits'=>$tabEcrits));
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
