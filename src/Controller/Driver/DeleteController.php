<?php declare(strict_types=1);

namespace App\Controller\Driver;

use App\Entity\Driver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/drivers/{id}', name: 'driver_delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
#[IsGranted('ROLE_ADMIN')]
final class DeleteController extends AbstractController
{
    public function __invoke(
        Driver $driver,
        EntityManagerInterface $em,
        Request $request,
    ): Response
    {
        if ($this->isCsrfTokenValid('delete' . $driver->id, $request->getPayload()->getString('_token'))) {
            $em->remove($driver);
            $em->flush();
            $this->addFlash('success', 'Fahrer wurde gelöscht.');
        }

        return $this->redirectToRoute('driver_index');
    }
}
