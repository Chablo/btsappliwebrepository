<?php

namespace btsappli\StagesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('btsappliStagesBundle:Default:index.html.twig', array('name' => $name));
    }
}
