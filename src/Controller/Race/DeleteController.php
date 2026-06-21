<?php declare(strict_types=1);

namespace App\Controller\Race;

use App\Entity\Race;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{id}', name: 'race_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
#[IsGranted('ROLE_ADMIN')]
final class DeleteController extends AbstractController
{
    public function __invoke(
        Race $race,
        EntityManagerInterface $em,
        Request $request,
    ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $race->id, $request->getPayload()->getString('_token'))) {
            $em->remove($race);
            $em->flush();
            $this->addFlash('success', 'Rennen wurde gelöscht.');
        }

        return $this->redirectToRoute('race_index');
    }
}
