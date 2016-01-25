<?php

namespace btsappli\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('btsappliUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
