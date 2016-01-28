<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace btsappli\UserBundle\Form\Type;


use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends BaseType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('prenom', 'text', array('label'=> 'Prénom'))
            ->add('nom', 'text', array('label'=> 'Nom'))
            ->add('telephone', 'text', array('label' => 'Telephone'))
            ->add('dateNaiss', 'birthday', array('label' => 'Date de naissance'))
            ->add('adresse', 'text', array('label' => 'Adresse'))
            ->add('codePostal', 'text', array('label'=> 'Code postal'))
            ->add('ville', 'text', array('label' => 'Ville'))
            ->add('promotion', 'text', array('label' => 'Promotion'));
        }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'btsappli\UserBundle\Entity\User'
        ));
    }
    
    // BC for SF < 3.0
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'btsappli_user_registration';
    }
}
