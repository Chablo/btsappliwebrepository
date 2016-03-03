<?php

namespace btsappli\StagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntrepriseRechercheForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder
            ->add('motCle', 'text', array('label' => 'Nom de l\'entreprise'));
    }
    
    public function getName()
    {        
        return 'entrepriseRecherche';
    }
}

