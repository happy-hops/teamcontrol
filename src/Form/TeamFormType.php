<?php

namespace App\Form;

use App\Entity\Team;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TeamFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'       => 'Teamname',
                'attr'        => ['class' => 'tc-input', 'placeholder' => 'Scuderia Siegburg'],
                'label_attr'  => ['class' => 'tc-label'],
                'row_attr'    => ['class' => 'tc-field'],
                'constraints' => [new NotBlank()],
            ])
            ->add('teamLead', TextType::class, [
                'label'       => 'Teamleiter',
                'attr'        => ['class' => 'tc-input', 'placeholder' => 'Max Mustermann'],
                'label_attr'  => ['class' => 'tc-label'],
                'row_attr'    => ['class' => 'tc-field'],
                'constraints' => [new NotBlank()],
            ])
            ->add('logo', FileType::class, [
                'label'      => 'Logo',
                'required'   => false,
                'mapped'     => false,
                'attr'       => ['class' => 'tc-input', 'accept' => 'image/*'],
                'label_attr' => ['class' => 'tc-label'],
                'row_attr'   => ['class' => 'tc-field'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Team::class]);
    }
}
