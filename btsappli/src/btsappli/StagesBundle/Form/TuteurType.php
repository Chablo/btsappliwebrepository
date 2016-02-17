<?php

namespace btsappli\StagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TuteurType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',                    'text')
            ->add('prenom',                 'text')
            ->add('adresseMail',            'email')
            ->add('telephone',              'number')
            ->add('infosComplementaires',   'textarea')
            ->add('fonction',               'text')
            ->add('entreprise')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'btsappli\StagesBundle\Entity\Tuteur'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'btsappli_stagesbundle_tuteur';
    }
}
