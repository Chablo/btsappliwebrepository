<?php

namespace btsappli\CCFBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use btsappli\CCFBundle\Entity\Ecrit;
use btsappli\CCFBundle\Entity\Oral;
use btsappli\CCFBundle\Form\EcritType;
use btsappli\CCFBundle\Form\OralType;

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
            for ($i = 0, $size = count($tabUsers) ; $i < $size ; ++$i)
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
    
    public function ajoutOralAction(Request $requeteUtilisateur)
    {
        // On créé un objet Oral "vide"
        $oral = new Oral();
        
        // Création du formulaire permettant de saisir un oral
        $formulaireOral = $this->createForm(new OralType, $oral);
        
        // Enregistrement des données dans $oral dès soumission du formulaire
        $formulaireOral->handleRequest($requeteUtilisateur);
        
        // Si le formulaire a été soumis et que les données sont valides
        if($formulaireOral->isValid())
        {
            // On récupère le repository de l'entité User
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            $repositoryUser=$gestionnaireEntite->getRepository('btsappliUserBundle:User');
            
            // On récupère la promotion concernée par l'oral
            $promotion = $oral->getPromotion();
            
            // On récupère les User concernés par l'oral
            $tabUsers = $repositoryUser->findByPromotion($promotion);
            
            // On récupère la date du formulaire
            $date = ($oral->getDate());
            // On établit l'heure du matin à 07:40 car ensuite incrémenté dans la boucle pour 08:30
            $heureMatin = (\DateTime::createFromFormat('H:i', '07:40'));
            $finMatin = (\DateTime::createFromFormat('H:i', '08:30'));
            // On établit l'heure de l'après-midi (12:40 car on va ensuite incrémenter pour 13:30)
            $heureAprem = (\DateTime::createFromFormat('H:i', '12:40'));
            $finAprem = (\DateTime::createFromFormat('H:i', '13:30'));
            $duree = (\DateInterval::createFromDateString('50 minutes'));
            
            // On va créer chaque oral et attribuer aux users récupérés
            for ($i = 0, $size = count($tabUsers) ; $i < $size ; ++$i)
            {
                // On créé l'Oral propre à l'étudiant
                $oralUser = new Oral();
                $oralUser->setMatiere($oral->getMatiere());
                $oralUser->setSalle($oral->getSalle());
                $oralUser->setPromotion($oral->getPromotion());
                
                // Si la fin de l'oral ne dépasse pas 12:00
                // -> matin
                if($finMatin <= (\DateTime::createFromFormat('H:i', '11:10')))
                {
                    // On incrémente les horaires de l'oral
                    $heureMatin -> add($duree);
                    $finMatin -> add($duree);
                    
                    // On attribue l'heure de début à l'oral
                    $oralUser->setHeure($heureMatin);
                }
                // Si la fin de l'oral dépasse 12:00
                // -> après-midi
                else
                {
                    // Si la fin ne dépasse pas 17:00
                    if($finAprem <= (\DateTime::createFromFormat('H:i', '16:10')))
                    {
                        // On incrémente les horaires de l'oral
                        $heureAprem -> add($duree);
                        $finAprem -> add($duree);
                        
                        // On attribue l'heure de début à l'oral
                        $oralUser->setHeure($heureAprem);
                    }
                    // Si la fin dépasse 17:00
                    else
                    {
                        // On incrémente la date
                        $date -> add(\DateInterval::createFromDateString('1 day'));
                        
                        // On redéfinit les heures du matin et de l'après-midi
                        $heureMatin = (\DateTime::createFromFormat('H:i', '08:30'));
                        $finMatin = (\DateTime::createFromFormat('H:i', '09:20'));
                        $heureAprem = (\DateTime::createFromFormat('H:i', '12:40'));
                        $finAprem = (\DateTime::createFromFormat('H:i', '13:30'));
                        
                        // On attribue l'heure de début à l'oral
                        $oralUser->setHeure($heureMatin);
                    }
                }
                
                // On attribue la date à l'oral
                $oralUser->setDate($date);
                
                // On enregistre en bd l'oral
                $gestionnaireEntite->persist($oralUser);
                $gestionnaireEntite->flush();
                
                // On lie cet oral à l'user
                $tabUsers[$i] -> addOraux($oralUser);
                
                // On enregistre en bd l'user
                $gestionnaireEntite->persist($tabUsers[$i]);
                $gestionnaireEntite->flush();
            }
            
            // On redirige vers la page de planning des CCF
            return $this->redirect($this->generateUrl('btsappli_CCF_planningOraux'));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet la représentation graphique du formulaire
        return $this->render('btsappliCCFBundle:CCF:ajoutOral.html.twig',
                                array('formulaireOral' => $formulaireOral -> createView()));
    }
    
    
    
    
    
    public function planningOrauxAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère tous les étudiants en cours avec leur oraux
        $tabUsers = $repositoryUser->getUsersEtOraux();
        
        // On récupère les promos en cours
        $tabPromos = $repositoryPromotion->findByEnCours(true);
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningOraux.html.twig', array(
                                            'tabUsers'=>$tabUsers,
                                            'tabPromos'=>$tabPromos));
    }
    
    public function planningOrauxPromoAction($idPromo)
    {
        // On récupère le repository de l'entité User
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les étudiants de la promo dont l'id est passé en paramètre
        $tabUsers = $repositoryUser->findByPromotion($idPromo);
        
        // On récupère les promos en cours
        $tabPromos = $repositoryPromotion->findByEnCours(true);
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningOraux.html.twig', array(
                                            'tabUsers'=>$tabUsers,
                                            'tabPromos'=>$tabPromos));
    }
    
    public function planningOrauxMatiereAction($matiere)
    {
        // On récupère le repository de l'entité User
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les étudiants de la promo dont l'id est passé en paramètre
        $tabUsers = $repositoryUser->findByPromotion($idPromo);
        
        // On récupère les promos en cours
        $tabPromos = $repositoryPromotion->findByEnCours(true);
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningOraux.html.twig', array(
                                            'tabUsers'=>$tabUsers,
                                            'tabPromos'=>$tabPromos));
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
    
    public function planningCCFAction($id)
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
