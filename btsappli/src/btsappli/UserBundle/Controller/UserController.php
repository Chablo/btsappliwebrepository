<?php

namespace btsappli\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function menuEtuAction()
    {
        return $this->render('btsappliUserBundle:User:menuEtu.html.twig');
    }
    
    public function menuAdminAction()
    {
        return $this->render('btsappliUserBundle:User:menuAdmin.html.twig');
    }
}
