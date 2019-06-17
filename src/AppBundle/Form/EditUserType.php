<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
/*
 * @codeCoverageIgnoreStart
 * @codeCoverageIgnoreEnd
 */
class EditUserType extends AbstractType
{
    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // To prevent an error under Codacy
        $options = null;
        // Build the form
        $builder
            ->add('username', TextType::class, [
                'constraints' => [
                    new Length([
                        'max' => 25,
                        'maxMessage' => "Le nom d'utilisateur ne peu comporter que {{ limit }} caractères",
                    ]),
                ],
                'label' => "Nom d'utilisateur",
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length(['max' => 4096]),
                    new Regex([
                        'pattern' => '/^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$/',
                        'message' => 'Le mot de passe doit comporter au moins 6 caractères,
                        minuscule, majuscule et numérique et caractère spéciaux.',
                    ]),
                ],
                'invalid_message' => 'Les deux mots de passe doivent correspondre.',
                'required' => false,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Tapez le mot de passe à nouveau'],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Length(['max' => 60]),
                    new Email(),
                ],
                'label' => 'Adresse email',
            ])
            ->add('roles', ChoiceType::class, [
                'constraints' => [new NotBlank()],
                'label' => 'Rôle de l\'utilisateur',
                'choices' => [
                    'utilisateur' => 'ROLE_USER',
                    'administrateur' => 'ROLE_ADMIN',
                ],
            ])
        ;
        // For the user role
        // Transforme an array to string OR string to array
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    // transform the array to a string
                    return $tagsAsArray[0];
                },
                function ($tagsAsString) {
                    // transform the string back to an array
                    return [$tagsAsString];
                }
            ))
        ;
    }
}