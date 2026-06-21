<?php

namespace App\Form;

use App\Entity\Enum\RaceMode;
use App\Entity\Race;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class RaceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'       => 'Name',
                'attr'        => ['class' => 'tc-input', 'placeholder' => '24h Kart Siegburg 2026'],
                'label_attr'  => ['class' => 'tc-label'],
                'row_attr'    => ['class' => 'tc-field'],
                'constraints' => [new NotBlank()],
            ])
            ->add('mode', EnumType::class, [
                'label'      => 'Modus',
                'class'      => RaceMode::class,
                'choice_label' => fn (RaceMode $m) => $m->label(),
                'attr'       => ['class' => 'tc-input'],
                'label_attr' => ['class' => 'tc-label'],
                'row_attr'   => ['class' => 'tc-field'],
            ])
            ->add('scheduled', DateType::class, [
                'label'      => 'Datum',
                'widget'     => 'single_text',
                'required'   => false,
                'attr'       => ['class' => 'tc-input'],
                'label_attr' => ['class' => 'tc-label'],
                'row_attr'   => ['class' => 'tc-field'],
            ])
            ->add('prebookingOpen', CheckboxType::class, [
                'label'      => 'Vorbuchung offen',
                'required'   => false,
                'label_attr' => ['class' => ''],
                'row_attr'   => ['class' => 'tc-check'],
            ])
            ->add('duration', IntegerType::class, [
                'label'       => 'Renndauer (Min.)',
                'attr'        => ['class' => 'tc-input', 'min' => 1],
                'label_attr'  => ['class' => 'tc-label'],
                'row_attr'    => ['class' => 'tc-field'],
                'constraints' => [new Positive()],
            ])
            ->add('minTurn', IntegerType::class, [
                'label'       => 'Min. Runde (Min.)',
                'attr'        => ['class' => 'tc-input', 'min' => 1],
                'label_attr'  => ['class' => 'tc-label'],
                'row_attr'    => ['class' => 'tc-field'],
                'constraints' => [new Positive()],
            ])
            ->add('maxTurn', IntegerType::class, [
                'label'       => 'Max. Runde (Min.)',
                'attr'        => ['class' => 'tc-input', 'min' => 1],
                'label_attr'  => ['class' => 'tc-label'],
                'row_attr'    => ['class' => 'tc-field'],
                'constraints' => [new Positive()],
            ])
            ->add('maxDrive', IntegerType::class, [
                'label'       => 'Max. Fahrzeit (Min.)',
                'attr'        => ['class' => 'tc-input', 'min' => 1],
                'label_attr'  => ['class' => 'tc-label'],
                'row_attr'    => ['class' => 'tc-field'],
                'constraints' => [new Positive()],
            ])
            ->add('breakTime', IntegerType::class, [
                'label'       => 'Pause (Min.)',
                'attr'        => ['class' => 'tc-input', 'min' => 0],
                'label_attr'  => ['class' => 'tc-label'],
                'row_attr'    => ['class' => 'tc-field'],
                'constraints' => [new Positive()],
            ])
            ->add('waitingPeriod', IntegerType::class, [
                'label'       => 'Karenz (Min.)',
                'attr'        => ['class' => 'tc-input', 'min' => 0],
                'label_attr'  => ['class' => 'tc-label'],
                'row_attr'    => ['class' => 'tc-field'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Race::class]);
    }
}
