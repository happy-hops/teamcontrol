<?php declare(strict_types=1);

namespace App\Controller\Race;

use App\Entity\Race;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{id}/settings', name: 'race_settings', requirements: ['id' => '\d+'], methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class SettingsController extends AbstractController
{
    public function __invoke(Race $race): Response
    {
        return $this->render('race/settings.html.twig', ['race' => $race]);
    }
}
