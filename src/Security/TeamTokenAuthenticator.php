<?php

namespace App\Security;

use App\Form\TeamTokenFormType;
use App\Repository\RaceRepository;
use App\Repository\TeamRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class TeamTokenAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly FormFactoryInterface  $formFactory,
        private readonly TeamRepository        $teams,
        private readonly RaceRepository        $races,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {}

    /**
     * Nur aktiv bei POST auf die Token-Login-Route.
     * Der form_login-Authenticator für Admins greift auf app_login — keine Kollision.
     */
    public function supports(Request $request): bool
    {
        return $request->isMethod('POST')
            && $request->getPathInfo() === '/team-login';
    }

    public function authenticate(Request $request): Passport
    {
        // Formular validieren (inkl. Constraints aus TeamTokenFormType)
        $form = $this->formFactory->create(TeamTokenFormType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            // Ersten Validierungsfehler weitergeben, sonst generische Meldung
            $errors = $form->getErrors(deep: true);
            $message = iterator_count($errors) > 0
                ? $form->getErrors(deep: true)->current()->getMessage()
                : 'Ungültiger Team Token.';

            throw new CustomUserMessageAuthenticationException($message);
        }

        $token = strtoupper(trim($form->get('teamToken')->getData()));

        // Aktuelles Rennen ermitteln
        $race = $this->races->findCurrent();

        if ($race === null) {
            throw new CustomUserMessageAuthenticationException(
                'Kein aktives Rennen gefunden.'
            );
        }

        // Team anhand des Tokens im aktuellen Rennen suchen
        $team = $this->teams->findByTokenAndRace($token, $race);

        if ($team === null) {
            // Bewusst keine Info ob Rennen oder Token das Problem ist
            throw new CustomUserMessageAuthenticationException(
                'Ungültiger Team Token. Bitte überprüfe deine Eingabe.'
            );
        }

        return new SelfValidatingPassport(
            new UserBadge(
                $token,
                fn (string $identifier) => new TeamUser($team),
            ),
            [
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        /** @var TeamUser $teamUser */
        $teamUser = $token->getUser();
        $team     = $teamUser->getTeam();

        return new RedirectResponse(
            $this->urlGenerator->generate('team_score', [
                'raceId' => $team->race->id,
                'token'  => $team->teamToken,
            ])
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        // Fehlermeldung in die Session, damit das Login-Template sie anzeigen kann
        $request->getSession()->getFlashBag()->add(
            'team_error',
            strtr($exception->getMessageKey(), $exception->getMessageData())
        );

        // Zurück zum Login, Token-Tab direkt öffnen per URL-Fragment
        return new RedirectResponse(
            $this->urlGenerator->generate('app_login', ['_fragment' => 'team'])
        );
    }
}
