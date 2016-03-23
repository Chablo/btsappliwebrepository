<?php

namespace btsappli\CCFBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    
     public function acceuilCCFAction()
    {
        return $this->render('btsappliCCFBundle:CCF:accueilCCF.html.twig');
    }
    
     public function planningCCFAdminAction()
    {
        return $this->render('btsappliCCFBundle:CCF:planningCCFAdmin.html.twig');
    }
    
     public function planningCCFEtuAction()
    {
        return $this->render('btsappliCCFBundle:CCF:planningCCFEtu.html.twig');
    }
}
