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
    public function choixTypeCCFAction()
    {
        return $this->render('btsappliCCFBundle:CCF:choisirTypeCCF.html.twig');
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
                $tabUsers[$i] -> setEcrit($ecrit);
                
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
            $debutMatin = (\DateTime::createFromFormat('H:i', '07:40'));
            $finMatin = (\DateTime::createFromFormat('H:i', '08:30'));
            // On établit l'heure de l'après-midi (12:40 car on va ensuite incrémenter pour 13:30)
            $debutAprem = (\DateTime::createFromFormat('H:i', '12:40'));
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
                    $debutMatin -> add($duree);
                    $finMatin -> add($duree);
                    
                    // On attribue l'heure de début et de fin à l'oral
                    $oralUser->setDebut($debutMatin);
                    $oralUser->setFin($finMatin);
                }
                // Si la fin de l'oral dépasse 12:00
                // -> après-midi
                else
                {
                    // Si la fin ne dépasse pas 17:00
                    if($finAprem <= (\DateTime::createFromFormat('H:i', '16:10')))
                    {
                        // On incrémente les horaires de l'oral
                        $debutAprem -> add($duree);
                        $finAprem -> add($duree);
                        
                        // On attribue l'heure de début et de fin à l'oral
                        $oralUser->setDebut($debutAprem);
                        $oralUser->setFin($finAprem);
                    }
                    // Si la fin dépasse 17:00
                    else
                    {
                        // On incrémente la date
                        $date -> add(\DateInterval::createFromDateString('1 day'));
                        
                        // On redéfinit les heures du matin et de l'après-midi
                        $debutMatin = (\DateTime::createFromFormat('H:i', '08:30'));
                        $finMatin = (\DateTime::createFromFormat('H:i', '09:20'));
                        $debutAprem = (\DateTime::createFromFormat('H:i', '12:40'));
                        $finAprem = (\DateTime::createFromFormat('H:i', '13:30'));
                        
                        // On attribue l'heure de début et de fin à l'oral
                        $oralUser->setDebut($debutMatin);
                        $oralUser->setFin($finMatin);
                    }
                }
                
                // On attribue la date à l'oral
                $oralUser->setDate($date);
                
                // On lie l'user à cet oral
                $oralUser -> setUser($tabUsers[$i]);
                
                // On enregistre en bd l'oral
                $gestionnaireEntite->persist($oralUser);
                $gestionnaireEntite->flush();
                
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
        // On récupère le repository de l'entité Oral
        $repositoryOral=$this->getDoctrine()->getManager()->getRepository('btsappliCCFBundle:Oral');
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère tous les étudiants en cours avec leur oraux
        $tabOraux = $repositoryOral->getUsersEtOraux();
        
        // On récupère les promos en cours
        $tabPromos = $repositoryPromotion->findByEnCours(true);
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningOraux.html.twig', array(
                                            'tabOraux'=>$tabOraux,
                                            'tabPromos'=>$tabPromos));
    }
    
    public function planningOrauxPromoAction($idPromo)
    {
        // On récupère le repository de l'entité Oral
        $repositoryOral=$this->getDoctrine()->getManager()->getRepository('btsappliCCFBundle:Oral');
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les oraux et users dont la promo dont l'id est passé en paramètre
        $tabOraux = $repositoryOral->getUsersEtOrauxPromo($idPromo);
        
        // On récupère les promos en cours
        $tabPromos = $repositoryPromotion->findByEnCours(true);
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningOraux.html.twig', array(
                                            'tabOraux'=>$tabOraux,
                                            'tabPromos'=>$tabPromos));
    }
    
    public function planningOrauxMatiereE5Action()
    {
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');

        // On récupère le repository de l'entité Oral
        $repositoryOral=$this->getDoctrine()->getManager()->getRepository('btsappliCCFBundle:Oral');
        
        // On récupère les oraux dont et users la matière est celle passée en paramètre
        $tabOraux = $repositoryOral->getUsersEtOrauxMatiereE5();
        
        // On récupère les promos en cours
        $tabPromos = $repositoryPromotion->findByEnCours(true);
        
        // On envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningOraux.html.twig', array(
                                            'tabOraux'=>$tabOraux,
                                            'tabPromos'=>$tabPromos));
    }
    
    public function planningOrauxMatiereE42Action()
    {
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');

        // On récupère le repository de l'entité Oral
        $repositoryOral=$this->getDoctrine()->getManager()->getRepository('btsappliCCFBundle:Oral');
        
        // On récupère les oraux dont et users la matière est celle passée en paramètre
        $tabOraux = $repositoryOral->getUsersEtOrauxMatiereE42();
        
        // On récupère les promos en cours
        $tabPromos = $repositoryPromotion->findByEnCours(true);
        
        // On envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningOraux.html.twig', array(
                                            'tabOraux'=>$tabOraux,
                                            'tabPromos'=>$tabPromos));
    }
    
    public function reinitialiserOrauxAction()
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion=$gestionnaireEntite->getRepository('btsappliUserBundle:Promotion');

        // On récupère le repository de l'entité Oral
        $repositoryOral=$gestionnaireEntite->getRepository('btsappliCCFBundle:Oral');
        
        // On récupère tous les oraux
        $tabOraux = $repositoryOral->findAll();
        
        for ($i = 0, $size = count($tabOraux);$i < $size; ++$i)
        {
            // On supprime l'oral
            $gestionnaireEntite->remove($tabOraux[$i]);
            $gestionnaireEntite->flush();
        }
        
        // On récupère le tableau d'oraux maintenant vide pour l'envoyer à la vue
        $tabOraux = $repositoryOral->findAll();
        
        // On récupère les promos en cours
        $tabPromos = $repositoryPromotion->findByEnCours(true);
        
        // On envoie le tableau d'oraux et de promos dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningOraux.html.twig', array(
                                            'tabOraux'=>$tabOraux,
                                            'tabPromos'=>$tabPromos));
    }
    
    public function reinitialiserOrauxValidationAction()
    {
        // On affiche la vue pour valider la réinitialisation des oraux
        return $this->render('btsappliCCFBundle:CCF:reinitialiserOrauxValidation.html.twig');
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
    
    public function reinitialiserEcritsAction()
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();

        // On récupère le repository de l'entité Ecrit
        $repositoryEcrit=$gestionnaireEntite->getRepository('btsappliCCFBundle:Ecrit');
        
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
        
        // On récupère tous les écrits
        $tabEcrits = $repositoryEcrit->findAll();
        
        for ($i = 0, $size = count($tabEcrits);$i < $size; ++$i)
        {
            // On réupère la promotion de l'écrit
            $promo = $tabEcrits[$i]->getPromotion();
            
            // On récupère les étudiants de cette promo
            $tabUsers = $repositoryUser->findByPromotion($promo);
            
            for ($j = 0, $sizeUsers = count($tabUsers);$j < $sizeUsers; ++$j)
            {
                // On met à NULL l'écrit de l'user
                $tabUsers[$j]->setEcrit(null);
            }
            
            // On supprime l'écrit
            $gestionnaireEntite->remove($tabEcrits[$i]);
            $gestionnaireEntite->flush();
        }
        
        // On récupère le tableau d'écrits maintenant vide pour l'envoyer à la vue
        $tabEcrits = $repositoryEcrit->findAll();
        
        // On envoie le tableau des écrits dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningEcrits.html.twig', array('tabEcrits'=>$tabEcrits));
    }
    
    public function reinitialiserEcritsValidationAction()
    {
        // On affiche la vue pour valider la réinitialisation des écrits
        return $this->render('btsappliCCFBundle:CCF:reinitialiserEcritsValidation.html.twig');
    }
    
    public function modifierEcritAction($id, Request $requeteUtilisateur)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();

        // On récupère le repository de l'entité Ecrit
        $repositoryEcrit=$gestionnaireEntite->getRepository('btsappliCCFBundle:Ecrit');
        
        // On récupère l'écrit choisi précédemment par l'administrateur
        $ecrit=$repositoryEcrit->find($id);
        
        // Création du formulaire permettant de modifier un écrit
        $formulaireEcrit = $this->createForm(new EcritType, $ecrit);
        
        // Enregistrement des données dans $ecrit dès soumission du formulaire
        $formulaireEcrit->handleRequest($requeteUtilisateur);
        
        // Si le formulaire a été soumis et que les données sont valides
        if($formulaireEcrit->isValid())
        {
            // On enregistre l'objet $écrit en base de données
            $gestionnaireEntite->persist($ecrit);
            $gestionnaireEntite->flush();
            // On redirige vers la page de planning des écrits
            return $this->redirect($this->generateUrl('btsappli_CCF_planningEcrits'));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet la représentation graphique du formulaire
        return $this -> render('btsappliCCFBundle:CCF:modificationEcrit.html.twig',
                        array('formulaireEcrit' => $formulaireEcrit -> createView(),
                              'ecrit' => $ecrit));
    }
    
    public function supprimerEcritAction($id)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();

        // On récupère le repository de l'entité Ecrit
        $repositoryEcrit=$gestionnaireEntite->getRepository('btsappliCCFBundle:Ecrit');
        
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
        
        // On récupère l'écrit qu'on veut supprimer
        $ecrit=$repositoryEcrit->find($id);
        
        // On réupère la promotion de l'écrit
        $promo = $ecrit->getPromotion();
            
        // On récupère les étudiants de cette promo
        $tabUsers = $repositoryUser->findByPromotion($promo);
            
        for ($j = 0, $sizeUsers = count($tabUsers);$j < $sizeUsers; ++$j)
        {
            // On met à NULL l'écrit de l'user
            $tabUsers[$j]->setEcrit(null);
        }
        
        // On supprime cet écrit de la BD
        $gestionnaireEntite->remove($ecrit);
        $gestionnaireEntite->flush();
        
        // On récupère tous les écrits pour transmettre à la vue
        $tabEcrits = $repositoryEcrit->findAll();
        
        // On redirige vers la page de planning des écrits
        return $this->render('btsappliCCFBundle:CCF:planningEcrits.html.twig', array('tabEcrits'=>$tabEcrits));
    }
    
    public function supprimerEcritValidationAction($id)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();

        // On récupère le repository de l'entité Ecrit
        $repositoryEcrit=$gestionnaireEntite->getRepository('btsappliCCFBundle:Ecrit');
        
        // On récupère l'écrit qu'on veut supprimer
        $ecrit=$repositoryEcrit->find($id);
        
        // On redirige vers la page de validation de suppression de l'écrit
        return $this->render('btsappliCCFBundle:CCF:supprimerEcritValidation.html.twig', array('ecrit'=>$ecrit));
    }
    
    
    
     public function planningCCFEtudiantAction()
    {
        //on récupère l'étudiant courant
        $user=$this->getUser();
        
        //on récupère l'étudiant courant
        $id=$user->getId();
        
        // On récupère le repository de l'entité User
        $repositoryOral=$this->getDoctrine()->getManager()->getRepository('btsappliCCFBundle:Oral');
        
        // On récupère les oraux de user dont l'id est passé en paramètre
        $tabOraux = $repositoryOral->getOrauxUser($id);
        
        // On récupère l'écrit de user 
        $ecrit = $user->getEcrit();
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliCCFBundle:CCF:planningCCFEtu.html.twig', array(
                                                                'tabOraux'=>$tabOraux,
                                                                'ecrit'=>$ecrit));
    }
    
    
}
