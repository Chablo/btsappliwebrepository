<?php

namespace btsappli\StagesBundle\Controller;

use StagesBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StagesController extends Controller
{
    public function entrepriseFormulaireAction(Request $request)
    {
        $entreprise = new Entreprise();

        $form = $this->createFormBuilder($entreprise)
            ->add('nom', 'text')
            ->add('adresse', 'text')
            ->add('codePostal', 'number')
            ->add('ville', 'text')
            ->add('pays', 'text')
            ->add('adresseMail', 'email')
            ->add('telephone', 'number')
            ->add('fax', 'number')
            ->add('description', 'text')
            ->add('save', 'submit', array('label' => 'Create Task'))
            ->getForm();
        
        if ($form->isSubmitted() && $form->isValid()) {
        // ... perform some action, such as saving the task to the database

        return $this->redirectToRoute('task_success');
        }

        return $this->render('btsappliStagesBundle:Stages:entrepriseFormulaire.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
