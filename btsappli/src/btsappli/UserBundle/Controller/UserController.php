<?php
namespace btsappli\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use btsappli\UserBundle\Entity\Promotion;
use btsappli\UserBundle\Form\PromotionType;

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
    
    

// ************************************************************************************************************
// ************* SUIVI ETUDIANTS ******************************************************************************
// ************************************************************************************************************

    public function suiviEtudiantsAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        // On récupère tous les étudiants avec leurs promotions
        $tabUsers = $repositoryUser->getEtudiantsEtPromotions();
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère toutes les promotions
        $tabPromotions = $repositoryPromotion->findAll();
        
        // On récupère les promotions en cours
        $tabPromotionsEnCours = $repositoryPromotion->findByEnCours(true);
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:suiviEtudiants.html.twig', array(
                                            'tabUsers'=>$tabUsers,
                                            'tabPromotions2'=>$tabPromotionsEnCours,
                                            'tabPromotions'=>$tabPromotions));
    }
    
    public function suiviEtudiantFiltrerAction($idPromo)
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère tous les étudiants dont la promotion a comme id le paramètre $idPromo
        $tabUsers = $repositoryUser->findByPromotion($idPromo);
        
        // On récupère toutes les promotions
        $tabPromotions = $repositoryPromotion->findAll();
        
        // On récupère les promotions en cours
        $tabPromotionsEnCours = $repositoryPromotion->findByEnCours(true);
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:suiviEtudiants.html.twig', array(
                                            'tabUsers'=>$tabUsers,
                                            'tabPromotions2'=>$tabPromotionsEnCours,
                                            'tabPromotions'=>$tabPromotions));
    }
    
    public function setPromoAction($id, $idPromo)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
        
        // On récupère l'étudiant dont l'id est le paramètre $id
        $user = $repositoryUser->find($id);
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $gestionnaireEntite->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère la promotion dont l'id est le paramètre $idPromo
        $promotion = $repositoryPromotion -> find($idPromo);
        
        // On attribue à l'étudiant la promotion récupérée
        $user->setPromotion($promotion); 
        $gestionnaireEntite->persist($user);
        $gestionnaireEntite->flush();
        
        // On récupère tous les étudiants avec leurs promotions
        $tabUsers = $repositoryUser->getEtudiantsEtPromotions();
        
        // On récupère toutes les promotions
        $tabPromotions = $repositoryPromotion->findAll();
        
        // On récupère les promotions en cours
        $tabPromotionsEnCours = $repositoryPromotion->findByEnCours(true);
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:suiviEtudiants.html.twig', array(
                                            'tabUsers'=>$tabUsers,
                                            'tabPromotions'=>$tabPromotions,
                                            'tabPromotions2'=>$tabPromotionsEnCours));
    }
    
    public function suppressionEtudiantValidationAction($id)
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
    
        // On récupère l'user dont l'id est le paramètre $id
        $user = $repositoryUser->find($id);
        
        return $this->render('btsappliUserBundle:User:suppressionEtudiantValidation.html.twig',
                                array('user' => $user));
    }
    
    public function suppressionEtudiantAction($id)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
        
        // On récupère le repository de l'entité Oral
        $repositoryOral = $gestionnaireEntite->getRepository('btsappliCCFBundle:Oral');
    
        // On récupère l'étudiant dont l'id est le paramètre $id
        $user = $repositoryUser->find($id);
        
        // On récupère les oraux dont l'id de l'user est le paramètre $id
        $tabOraux = $repositoryOral->findByUser($id);
        
        // Pour chaque oral possédé par l'étudiant
        for($i = 0, $size = count($tabOraux);$i < $size; ++$i)
        {
            // On supprime cet oral
            $gestionnaireEntite->remove($tabOraux[$i]);
        }
        
        // On supprime cet étudiant
        $gestionnaireEntite->remove($user);
        $gestionnaireEntite->flush();
    
        // On récupère tous les étudiants
        $tabUsers = $repositoryUser->getEtudiants();
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les promotions en cours
        $tabPromotions2 = $repositoryPromotion->findByEnCours(true);
        
        // On récupère toutes les promotions
        $tabPromotions = $repositoryPromotion -> findAll();
        
        // On transmet les tableaux à la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:suiviEtudiant.html.twig', 
                             array('tabUsers' => $tabUsers,
                                   'tabPromotions' => $tabPromotions,
                                   'tabPromotions2'=>$tabPromotions2));
    }
    
    
// ************************************************************************************************************
// ************* PROMOTIONS ***********************************************************************************
// ************************************************************************************************************

    public function ajoutPromoAction(Request $requeteUtilisateur)
    {
        // On créé un objet Promotion "vide"
        $promotion = new Promotion();
        
        // Création du formulaire permettant de saisir une promotion
        $formulairePromotion = $this->createForm(new PromotionType, $promotion);
        
        // Enregistrement des données dans $promotion dès soumission du formulaire
        $formulairePromotion->handleRequest($requeteUtilisateur);
        
        // Si le formulaire a été soumis et que les données sont valides
        if($formulairePromotion->isValid())
        {
            // On récupère le gestionnaire d'entité
            $gestionnaireEntite = $this->getDoctrine()->getManager();
            
            // On récupère le repository de l'entité Promotion
            $repositoryPromotion = $gestionnaireEntite->getRepository('btsappliUserBundle:Promotion');
            
            // On calcule l'année de la promotion terminée en récupérant l'année de la nouvelle promotion
            $anneePromo = ($promotion->getanneePromo()) - 2;
            
            // On récupère la promotion qui est terminée
            $anciennePromo = $repositoryPromotion -> getAnciennePromo($anneePromo);
            
            // On passe cette promotion à non en cours
            $anciennePromo -> setEnCours(0);
            
            // On enregistre l'objet $anciennePromo en base de données
            $gestionnaireEntite->persist($anciennePromo);
            $gestionnaireEntite->flush();
            
            // On enregistre l'objet $promotion en base de données
            $gestionnaireEntite->persist($promotion);
            $gestionnaireEntite->flush();
            
            // On redirige vers la page de suivi de stages
            return $this->redirect($this->generateUrl('btsappli_utilisateurs_suiviStages'));
        }
        
        // On appelle la vue chargée d'afficher le formulaire et on lui transmet la représentation graphique du formulaire
        return $this -> render('btsappliUserBundle:User:ajoutPromotion.html.twig',
                        array('formulairePromotion' => $formulairePromotion -> createView()));
    }

    

// **************************************************************************************************************
// ************* SUIVI STAGES ***********************************************************************************
// **************************************************************************************************************

    public function suiviStagesAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
    
        // On récupère tous les users avec leur stage
        $tabUsersEtStages = $repositoryUser->getUsersEtStages();
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les promotions en cours
        $tabPromotions = $repositoryPromotion->findByEnCours(true);
    
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages,
                                   'tabPromotions' => $tabPromotions)); 
    }
    
    public function voirStageEtuAction($id)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
    
        // On récupère l'user dont l'id est le paramètre $id
        $user = $repositoryUser->find($id);
        
        // On transmet le stage à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:vueStageEtu.html.twig', array('user' => $user));
    }
    
    public function setEtatConvAction($id,$etatConv)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
    
        // On récupère l'user dont l'id est le paramètre $id
        $user = $repositoryUser->find($id);
        $stage = $user->getStage();
        
        // On met la convention à la valeur correspondante
        if($etatConv==1) {
            $stage->setetatConvention(1);
        } elseif($etatConv==2) {
            $stage->setetatConvention(2);
        } elseif($etatConv==3) {
            $stage->setetatConvention(3);

        }
        
        // On le répercute dans la BD
        $gestionnaireEntite->persist($stage);
        $gestionnaireEntite->flush();
        
        // On transmet le stage à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:vueStageEtu.html.twig', array('user' => $user));
    }
    
    public function suiviStagesFiltrerPromoAction($idPromo)
    {
        //on récupère le repository de l'entité fos_user
        $repositoryUser=$this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        // on récupère tous les étudiants dont la promo est l'id $idPromo
        $tabUsersEtStages = $repositoryUser->findBy(array('promotion' => $idPromo), array('nom'=>'ASC'));
        
        //on récupère le repository de l'entité promo
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les promotions en cours
        $tabPromotions = $repositoryPromotion->findByEnCours(true);
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', array(
                                            'tabUsersEtStages' => $tabUsersEtStages,
                                            'tabPromotions'=>$tabPromotions));
    }
    
    public function suiviStagesFiltrerConventionValideeAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
    
        // On récupère tous les users dont le stage est validée
        $tabUsersEtStages = $repositoryUser->findByEtatConventionValidee();     
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les promotions en cours
        $tabPromotions = $repositoryPromotion->findByEnCours(true);
    
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages,
                                   'tabPromotions' => $tabPromotions)); 
    }
    
    public function suiviStagesFiltrerConventionNonValideeAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
    
        // On récupère tous les users dont le stage est validée
        $tabUsersEtStages = $repositoryUser->findByEtatConventionNonValidee();   
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les promotions en cours
        $tabPromotions = $repositoryPromotion->findByEnCours(true);
    
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages,
                                   'tabPromotions' => $tabPromotions)); 
    }
    
    public function suiviStagesFiltrerConventionSigneeAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
    
        // On récupère tous les users dont le stage est validée
        $tabUsersEtStages = $repositoryUser->findByEtatConventionSignee();
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les promotions en cours
        $tabPromotions = $repositoryPromotion->findByEnCours(true);
    
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages,
                                   'tabPromotions' => $tabPromotions)); 
    }
    
    public function suiviStagesFiltrerPasStageAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
    
        // On récupère tous les users qui n'ont pas de stage
        $tabUsersEtStages = $repositoryUser->findByPasStage();
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les promotions en cours
        $tabPromotions = $repositoryPromotion->findByEnCours(true);
    
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages,
                                   'tabPromotions' => $tabPromotions)); 
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
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les promotions en cours
        $tabPromotions = $repositoryPromotion->findByEnCours(true);
        
        // On transmet l'entreprise à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages,
                                   'tabPromotions' => $tabPromotions));
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
    
    public function reinitialiserStagesAction()
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();
        
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
    
        // On récupère tous les users
        $tabUsers = $repositoryUser->findAll();
        
        for ($i = 0, $size = count($tabUsers);$i < $size; ++$i)
        {
            $stage = $tabUsers[$i]->getStage();
            
            if ($stage!=null)
            {
                // On met à NULL le stage de l'user
                $tabUsers[$i]->setStage(null);
                
                // On supprime le stage récupéré de la BD
                $gestionnaireEntite->remove($stage);
                $gestionnaireEntite->flush();
            }
        }
        // On récupère tous les users avec leur stage
        $tabUsersEtStages = $repositoryUser->getUsersEtStages();
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les promotions en cours
        $tabPromotions = $repositoryPromotion->findByEnCours(true);
        
        // On transmet les users et stages à la vue
        return $this->render('btsappliUserBundle:User:suiviStages.html.twig', 
                             array('tabUsersEtStages' => $tabUsersEtStages,
                                   'tabPromotions' => $tabPromotions));
    }
    
    public function reinitStagesValidationAction()
    {
        return $this->render('btsappliUserBundle:User:suiviStagesReinitValidation.html.twig');
    }
    
    
// **************************************************************************************************************
// ************* GERER INSCRIPTIONS *****************************************************************************
// **************************************************************************************************************
    
    public function validationUsersAction()
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
        
        // On récupère toutes les inscriptions demandant une validation
        $tabUser = $repositoryUser->getUsersNonValides();
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les promotions en cours
        $tabPromotions = $repositoryPromotion->findByEnCours(true);
        
        //on envoie la liste des étudiants demandant une inscription dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:validerInscriptions.html.twig', 
                                                              array('tabUser'=>$tabUser,
                                                                    'tabPromotions' => $tabPromotions));
    }
    
    
    public function refuserInscriptionAction($id)
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

        // On récupère tous les users non validés
        $tabUser = $repositoryUser->getUsersNonValides();  
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $gestionnaireEntite->getRepository('btsappliUserBundle:Promotion');
        
        // On récupère les promos en cours
        $tabPromotions = $repositoryPromotion -> findByEnCours(true);
        
        // On transmet les étudiants non valide et les promo à la vue chargée de l'afficher
        return $this->render('btsappliUserBundle:User:validerInscriptions.html.twig', array(
                                                                'tabUser'=>$tabUser,
                                                                'tabPromotions'=>$tabPromotions));
    }
    
    public function refuserInscriptionValidationAction($id)
    {
        // On récupère le repository de l'entité User
        $repositoryUser = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:User');
    
        // On récupère l'user dont l'id est le paramètre $id
        $user = $repositoryUser->find($id);
        
        return $this->render('btsappliUserBundle:User:refuserInscriptionValidation.html.twig',
                                array('user' => $user));
    }

    public function accepterInscriptionAction($id)
    {
        // On récupère le gestionnaire d'entité
        $gestionnaireEntite = $this->getDoctrine()->getManager();
            
        // On récupère le repository de l'entité User
        $repositoryUser = $gestionnaireEntite->getRepository('btsappliUserBundle:User');
        
        // On récupère l'user dont l'id est le paramètre $id
        $user = $repositoryUser->find($id);
        
        $email = $user->getEmail();
        
        // On passe à ROLE_ETU pour valider l'inscription
        $user->addRole('ROLE_ETU');
        
        $gestionnaireEntite->persist($user);
        $gestionnaireEntite->flush();
        
        $message = \Swift_Message::newInstance()
        ->setSubject('Bienvenue sur MyCG')
        ->setFrom('mybtscg@gmail.com')
        ->setTo($email)
        ->setBody(
            $this->renderView(
                'btsappliUserBundle:User:email.txt.twig',
                array('id' => $id)
            )
        )
    ;
    $this->get('mailer')->send($message);
    
        // On récupère les users non validés
        $tabUser = $repositoryUser->getUsersNonValides();
            
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
            
        // On récupère les promos en cours
        $tabPromotions = $repositoryPromotion -> findByEnCours(true);
            
        // On transmet les user à la vue
        return $this->render('btsappliUserBundle:User:validerInscriptions.html.twig', array(
                                                                'tabUser'=>$tabUser,
                                                                'tabPromotions'=>$tabPromotions));
    }
    
    public function setPromoInscriptionAction($id, $idPromo)
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
        
        // On récupère tous les users qui ne sont pas validés
        $tabUser = $repositoryUser->getUsersNonValides();
        
        // On récupère le repository de l'entité Promotion
        $repositoryPromotion = $this->getDoctrine()->getManager()->getRepository('btsappliUserBundle:Promotion');
        
        // on récupère les promos en cours
        $tabPromotions = $repositoryPromotion -> findByEnCours(true);
        
        //on envoie la liste des étudiants dans la vue chargée de les afficher
        return $this->render('btsappliUserBundle:User:validerInscriptions.html.twig', array(
                                            'tabUser'=>$tabUser,
                                            'tabPromotions'=>$tabPromotions));
    }
    
    public function etudiantNonValideAction()
    {
        return $this->render('btsappliUserBundle:User:etudiantNonValide.html.twig');
    }
}