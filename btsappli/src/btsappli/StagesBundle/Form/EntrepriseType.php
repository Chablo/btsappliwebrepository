<?php

namespace btsappli\StagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntrepriseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',            'text')
            ->add('representant',   'text')
            ->add('adresse',        'text')
            ->add('codePostal',     'text')
            ->add('ville',          'text')
            ->add('pays',           'text')
            ->add('adresseMail',    'email')
            ->add('telephone',      'number')
            ->add('fax',            'number')
            ->add('description',    'textarea')
            ->add('serviceAccueil', 'text')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'btsappli\StagesBundle\Entity\Entreprise'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'btsappli_stagesbundle_entreprise';
    }
}
