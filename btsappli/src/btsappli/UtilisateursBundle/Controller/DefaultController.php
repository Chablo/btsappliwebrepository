<?php

namespace btsappli\UtilisateursBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('btsappliUtilisateursBundle:Default:index.html.twig', array('name' => $name));
    }
}
