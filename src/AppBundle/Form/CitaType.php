<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CitaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tema')
                ->add('materia')
                ->add('expiracion', ChoiceType::class, array(
                    "label" => "Expiración",
                    "choices" => array(
                        "Esta Semana" => "0",
                        "Siguiente Semana" => "1",
                        "Esta Semana y la Siguiente" => "2", 
                        "Este Mes" => "3"
                        ),
                    "attr" => array(
                        "class" => "form-control"
                        )
                    )
                )
                ->add('cantidad', null, array(
                        'label' => 'Cantidad de alumnos',
                        'attr' => array(
                            "value" => 1,
                        ),
                    )
                )
                ->add('horario', HiddenType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Cita'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_cita';
    }


}
