<?php

namespace App\Controller;

use App\Form\TeamTokenFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'user_login')]
    public function loginAction(AuthenticationUtils $authUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('race_index');
        }

        return $this->render('security/login.html.twig', [
            'tokenForm'     => $this->createForm(TeamTokenFormType::class),
            'error'         => $authUtils->getLastAuthenticationError(),
            'last_username' => $authUtils->getLastUsername(),
        ]);
    }

    /**
     * GET /team-login landet hier wenn der Authenticator supports() = false zurückgibt.
     * POST /team-login wird vom TeamTokenAuthenticator abgefangen — nie erreicht.
     */
    #[Route('/team-login', name: 'team_login', methods: ['GET', 'POST'])]
    public function teamLoginAction(): Response
    {
        return $this->redirectToRoute('user_login', ['_fragment' => 'team']);
    }

    #[Route('/logout', name: 'logout')]
    public function logoutAction(): never
    {
        throw new \LogicException('This method should never be reached.');
    }
}
