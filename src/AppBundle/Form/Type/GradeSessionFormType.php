<?php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
/**
 * ProfileFormType.
 */
class GradeSessionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start_date', Type\TextType::class)
            ->add('end_date', Type\TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => 'AppBundle\Form\Model\GradeSessionFormModel',
          'csrf_protection' => false,
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_grade_session';
    }
}
