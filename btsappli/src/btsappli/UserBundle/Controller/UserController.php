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
}
