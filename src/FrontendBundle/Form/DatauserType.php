<?php
// src/RythmBundle/Form/DatauserType.php

namespace FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class DatauserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('avatar', FileType::class, array(
                'label' => 'Folder (jpg file)',
                'data_class' => null,
                'required' => false,

            ))
            ->add('cover', FileType::class, array(
                'label' => 'Folder (jpg file)',
                'data_class' => null,
                'required' => false,

            ))
        ;
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FrontendBundle\Entity\Datauser',
        ));
    }
}