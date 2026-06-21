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

#[Route('/drivers', name: 'driver_create', methods: ['POST'])]
#[IsGranted('ROLE_ADMIN')]
final class CreateController extends AbstractController
{
    public function __invoke(
        EntityManagerInterface $em,
        Request                $request
    ): Response
    {
        $form = $this->createForm(DriverFormType::class)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $driver = $form->getData();
            assert($driver instanceof Driver);

            $em->persist($driver);
            $em->flush();

            $this->addFlash('success', 'Fahrer wurde erfolgreich angelegt.');
            return $this->redirectToRoute('driver_index');
        }

        return $this->render('driver/new.html.twig', [
            'form' => $form,
        ]);
    }
}
