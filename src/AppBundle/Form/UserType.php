<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', null, array('attr' => array("required" => true)))
                ->add('aPaterno', null, array("label" => 'Apellido Paterno','attr' => array("required" => true)))
                ->add('aMaterno', null, array("label" => 'Apellido Materno','attr' => array("required" => true)))
                ->add('email', TextType::class, array("label" => 'Correo Electrónico','attr' => array("required" => true)))
                ->add('save', SubmitType::class, array('label' => 'Guardar','attr' => array("required" => true)));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
