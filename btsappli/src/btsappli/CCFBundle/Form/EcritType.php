<?php

namespace btsappli\CCFBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use btsappli\UserBundle\Entity\PromotionRepository;

class EcritType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('duree',      'time', array(
                        'label' => 'Durée'))
            ->add('debut',      'time', array(
                        'label' => 'Début'))
            ->add('salle',      'entity', array(
                        'class' => 'btsappliCCFBundle:Salle',
                        'property' => 'nom'))
            ->add('promotion',  'entity', array(
                        'label' => 'Promotion concernée',
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
            'data_class' => 'btsappli\CCFBundle\Entity\Ecrit'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'btsappli_ccfbundle_ecrit';
    }
}
