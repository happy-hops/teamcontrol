<?php

namespace App\Controller;

use App\Entity\Driver;
use App\Form\DriverFormType;
use App\Repository\DriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/drivers')]
#[IsGranted('ROLE_ADMIN')]
class DriverController extends AbstractController
{
    public function __construct(
        private readonly DriverRepository     $drivers,
        private readonly EntityManagerInterface $em,
    ) {}

    #[Route('', name: 'driver_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $search  = $request->query->getString('q');
        $drivers = $search
            ? $this->drivers->search($search)
            : $this->drivers->findAllOrderedByName();

        return $this->render('driver/index.html.twig', [
            'drivers' => $drivers,
            'search'  => $search,
        ]);
    }

    #[Route('/new', name: 'driver_new', methods: ['GET'], priority: 1)]
    public function new(): Response
    {
        $form = $this->createForm(DriverFormType::class, new Driver(''));

        return $this->render('driver/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('', name: 'driver_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $driver = new Driver('');
        $form   = $this->createForm(DriverFormType::class, $driver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($driver);
            $this->em->flush();

            $this->addFlash('success', 'Fahrer wurde erfolgreich angelegt.');
            return $this->redirectToRoute('driver_index');
        }

        return $this->render('driver/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'driver_edit', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function edit(Driver $driver): Response
    {
        $form = $this->createForm(DriverFormType::class, $driver);

        return $this->render('driver/edit.html.twig', [
            'driver' => $driver,
            'form'   => $form,
        ]);
    }

    #[Route('/{id}', name: 'driver_update', methods: ['POST', 'PUT', 'PATCH'], requirements: ['id' => '\d+'])]
    public function update(Request $request, Driver $driver): Response
    {
        $form = $this->createForm(DriverFormType::class, $driver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'Fahrer wurde erfolgreich aktualisiert.');
            return $this->redirectToRoute('driver_index');
        }

        return $this->render('driver/edit.html.twig', [
            'driver' => $driver,
            'form'   => $form,
        ]);
    }

    #[Route('/{id}', name: 'driver_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Driver $driver): Response
    {
        $this->em->remove($driver);
        $this->em->flush();

        $this->addFlash('success', 'Fahrer wurde gelöscht.');
        return $this->redirectToRoute('driver_index');
    }
}
