<?php declare(strict_types=1);

namespace App\Controller\Race;

use App\Entity\Race;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{id}/finish', name: 'race_finish', requirements: ['id' => '\d+'], methods: ['POST'])]
#[IsGranted('ROLE_ADMIN')]
final class FinishController extends AbstractController
{
    public function __invoke(
        Race $race,
        EntityManagerInterface $em,
    ): Response
    {
        try {
            $race->finish();
            $em->flush();
            $this->addFlash('success', 'Das Rennen wurde beendet.');
        } catch (\LogicException $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('race_settings', ['id' => $race->id]);
    }
}
