<?php

namespace btsappli\StagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use btsappli\StagesBundle\Entity\EntrepriseRepository;
use btsappli\StagesBundle\Entity\TuteurRepository;

class StageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateDebut',  'date',   array('years' => range(date('Y'), date('Y')+1)))
            ->add('dateFin',    'date',   array('years' => range(date('Y'), date('Y')+1)))
            //->add('etatConvention')
            ->add('entreprise', 'entity', array(
                        'label' => 'Choix de l\'entreprise',
                        'class' => 'btsappliStagesBundle:Entreprise',
                        'query_builder' => function (EntrepriseRepository $er) {
                                                return $er->createQueryBuilder('e')->orderBy('e.nom', 'ASC');
                                            },
                        'property' => 'NomVille',
                        'multiple' => false,
                        'expanded' => false))
            /*->add('tuteur', 'entity', array(
                        'label' => 'Choix du tuteur',
                        'class' => 'btsappliStagesBundle:Tuteur',
                        'property' => 'NomPrenom',
                        'query_builder' => function(TuteurRepository $tr) use ($id)
                                            {
                                                return $tr->getTuteursDUneEntreprise($id);
                                            },
                        'multiple' => false,
                        'expanded' => false))*/
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'btsappli\StagesBundle\Entity\Stage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'btsappli_stagesbundle_stage';
    }
}
