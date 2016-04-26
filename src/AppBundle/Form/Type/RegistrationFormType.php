<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * RegistrationFormType.
 */
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', Type\TextType::class)
            ->add('email', Type\EmailType::class)
            ->add('plainPassword', Type\PasswordType::class)
            ->add('password', Type\PasswordType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Form\Model\RegistrationFormModel',
            'csrf_protection' => false,
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}
