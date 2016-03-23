<?php

namespace btsappli\CCFBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use btsappli\UserBundle\Entity\PromotionRepository;

class OralType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date',  'date')
            ->add('matiere',    'choice', 
                    array('label' => 'Matière',
                          'choices' => array('E42' => 'E42',
                                             'E5' => 'E5')))
            ->add('salle',      'choice',
                    array('choices' => array('Salle info J142' => 'Salle info J142',
                                             'Salle de préparation J143' => 'Salle de préparation J143')))
            ->add('promotion',  'entity', 
                    array('label' => 'Promotion concernée',
                          'class' => 'btsappliUserBundle:Promotion',
                          'property' => 'anneePromo',
                          'query_builder' => function (PromotionRepository $pr) {
                                return $pr->getPromoEnCours();
                          }))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'btsappli\CCFBundle\Entity\Oral'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'btsappli_ccfbundle_oral';
    }
}
