<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Symfony Security erwartet die Felder _username, _password und _remember_me.
 * Die Namen werden über mapped: false und attr[name] gesetzt,
 * damit das Standard-FormLogin-System greift ohne eigenen Authenticator.
 */
class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label'    => 'E-Mail',
                'mapped'   => false,
                'attr'     => [
                    'name'         => '_username',
                    'placeholder'  => 'name@domain.de',
                    'autocomplete' => 'email',
                    'autofocus'    => true,
                    'class'        => 'tc-input',
                ],
                'label_attr' => ['class' => 'tc-label'],
                'row_attr'   => ['class' => 'tc-field'],
            ])
            ->add('password', PasswordType::class, [
                'label'    => 'Passwort',
                'mapped'   => false,
                'attr'     => [
                    'name'         => '_password',
                    'placeholder'  => '••••••••',
                    'autocomplete' => 'current-password',
                    'class'        => 'tc-input',
                ],
                'label_attr' => ['class' => 'tc-label'],
                'row_attr'   => ['class' => 'tc-field'],
            ])
            ->add('rememberMe', CheckboxType::class, [
                'label'    => 'Angemeldet bleiben',
                'mapped'   => false,
                'required' => false,
                'attr'     => [
                    'name'  => '_remember_me',
                ],
                'label_attr' => ['class' => ''],
                'row_attr'   => ['class' => 'tc-check'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Kein Data-Class — Felder sind mapped: false
            // CSRF wird von Symfony Security selbst verwaltet (_csrf_token)
            'csrf_protection' => false,
        ]);
    }

    /**
     * Leerer Block-Prefix damit die Felder nicht unter "login_form[email]"
     * gerendert werden, sondern direkt als "_username" etc.
     */
    public function getBlockPrefix(): string
    {
        return '';
    }
}
