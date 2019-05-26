<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title',      TextType::class, array(
            'error_bubbling' => true))
        ->add('content',    TextareaType::class, array(
            'error_bubbling' => true,
            'attr'           => array(
                'rows'         => 5)
    ));
    }
}
