<?php declare(strict_types=1);

namespace App\Controller\Race;

use App\Entity\Race;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{id}', name: 'race_show', requirements: ['id' => '\d+'], methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class ShowController extends AbstractController
{
    public function __invoke(Race $race): Response
    {
        return $this->redirectToRoute('race_settings', ['id' => $race->id]);
    }
}
