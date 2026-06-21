<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class TeamTokenFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('teamToken', TextType::class, [
                'label'      => 'Team Token',
                'attr'       => [
                    'placeholder'  => 'ABCDEFGHIJ',
                    'maxlength'    => 8,
                    'autocomplete' => 'off',
                    'class'        => 'tc-input monospace',
                ],
                'label_attr' => ['class' => 'tc-label'],
                'row_attr'   => ['class' => 'tc-field'],
                'help'       => '8-stellige Kombination aus Buchstaben und Zahlen',
                'constraints' => [
                    new NotBlank(message: 'Bitte gib deinen Team Token ein.'),
                    new Length(
                        exactly: 8,
                        exactMessage: 'Der Team Token muss genau {{ limit }} Zeichen lang sein.',
                    ),
                    new Regex(
                        pattern: '/^[23456789ABCDEFGHJKLMNPQRSTUVWXYZ]{8}$/i',
                        message: 'Ungültiger Team Token.',
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_token_id' => 'team_login',
        ]);
    }
}
