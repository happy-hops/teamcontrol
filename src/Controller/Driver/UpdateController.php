<?php declare(strict_types=1);

namespace App\Controller\Driver;

use App\Entity\Driver;
use App\Form\DriverFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/drivers/{id}', name: 'driver_update', requirements: ['id' => '\d+'], methods: ['POST', 'PUT', 'PATCH'])]
#[IsGranted('ROLE_ADMIN')]
final class UpdateController extends AbstractController
{
    public function __invoke(
        Driver                 $driver,
        EntityManagerInterface $em,
        Request                $request,
    ): Response
    {
        $form = $this->createForm(DriverFormType::class, $driver)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Fahrer wurde erfolgreich aktualisiert.');
            return $this->redirectToRoute('driver_index');
        }

        return $this->render('driver/edit.html.twig', [
            'driver' => $driver,
            'form'   => $form,
        ]);
    }
}
