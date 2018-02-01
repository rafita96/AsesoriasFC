<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AlumnoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', null, array('required' => true))
                ->add('aPaterno', null, array('label' => "Apellido Paterno",'required' => true))
                ->add('aMaterno', null, array('label' => "Apellido Materno",'required' => true))
                ->add('correo', null, array('label' => "Correo Institucional",'required' => true))
                ->add('save', SubmitType::class, array('label' => 'Continuar'))
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Alumno'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_alumno';
    }


}
