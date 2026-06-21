<?php declare(strict_types=1);

namespace App\Controller\Security;

use App\Form\TeamTokenFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/login', name: 'user_login')]
final class LoginController extends AbstractController
{
    public function __invoke(AuthenticationUtils $authUtils): Response
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
}
