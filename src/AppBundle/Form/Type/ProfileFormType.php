<?php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * ProfileFormType.
 */
class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', Type\TextType::class)
            ->add('email', Type\EmailType::class)
            ->add('description', Type\EmailType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'AppBundle\Form\Model\ProfileFormModel',
          'csrf_protection' => false,
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}
