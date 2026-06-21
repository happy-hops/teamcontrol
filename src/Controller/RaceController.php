<?php

namespace App\Controller;

use App\Entity\Race;
use App\Enum\RaceMode;
use App\Form\RaceFormType;
use App\Repository\RaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races')]
#[IsGranted('ROLE_ADMIN')]
class RaceController extends AbstractController
{
    public function __construct(
        private readonly RaceRepository        $races,
        private readonly EntityManagerInterface $em,
    ) {}

    #[Route('/current', name: 'race_current', methods: ['GET'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function current(): Response
    {
        $race = $this->races->findCurrent();
        return $race
            ? $this->redirectToRoute('race_show', ['id' => $race->id])
            : $this->redirectToRoute('race_index');
    }

    #[Route('', name: 'race_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('race/index.html.twig', [
            'races' => $this->races->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'race_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Race $race): Response
    {
        return $this->redirectToRoute('race_settings', ['id' => $race->id]);
    }

    #[Route('/{id}/settings', name: 'race_settings', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function settings(Race $race): Response
    {
        return $this->render('race/settings.html.twig', ['race' => $race]);
    }

    #[Route('/{id}/overview', name: 'race_overview', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function overview(Race $race): Response
    {
        return $this->render('race/overview.html.twig', ['race' => $race]);
    }

    #[Route('/{id}/public', name: 'race_public_overview', methods: ['GET'], requirements: ['id' => '\d+'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function publicOverview(Race $race): Response
    {
        return $this->render('race/public_overview.html.twig', [
            'race'  => $race,
            'teams' => $race->teams,
        ]);
    }

    #[Route('/new', name: 'race_new', methods: ['GET'], priority: 1)]
    public function new(): Response
    {
        $race = new Race('', '', 540, 170, 40, 20, 45, 3, RaceMode::Both);
        $form = $this->createForm(RaceFormType::class, $race);

        return $this->render('race/new.html.twig', ['form' => $form]);
    }

    #[Route('', name: 'race_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $race = new Race('', '', 540, 170, 40, 20, 45, 3, RaceMode::Both);
        $form = $this->createForm(RaceFormType::class, $race);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $race->setSlug($this->generateSlug($race->name));
            $this->em->persist($race);
            $this->em->flush();
            $this->addFlash('success', 'Rennen wurde erfolgreich angelegt.');
            return $this->redirectToRoute('race_settings', ['id' => $race->id]);
        }

        return $this->render('race/new.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/edit', name: 'race_edit', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function edit(Race $race): Response
    {
        $form = $this->createForm(RaceFormType::class, $race);
        return $this->render('race/edit.html.twig', ['race' => $race, 'form' => $form]);
    }

    #[Route('/{id}', name: 'race_update', methods: ['POST', 'PUT', 'PATCH'], requirements: ['id' => '\d+'])]
    public function update(Request $request, Race $race): Response
    {
        $form = $this->createForm(RaceFormType::class, $race);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Rennen wurde aktualisiert.');
            return $this->redirectToRoute('race_settings', ['id' => $race->id]);
        }

        return $this->render('race/edit.html.twig', ['race' => $race, 'form' => $form]);
    }

    #[Route('/{id}/start', name: 'race_start', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function start(Race $race): Response
    {
        try {
            $race->start();
            $this->em->flush();
            $this->addFlash('success', 'Das Rennen wurde gestartet.');
        } catch (\LogicException $e) {
            $this->addFlash('danger', $e->getMessage());
        }
        return $this->redirectToRoute('race_settings', ['id' => $race->id]);
    }

    #[Route('/{id}/finish', name: 'race_finish', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function finish(Race $race): Response
    {
        try {
            $race->finish();
            $this->em->flush();
            $this->addFlash('success', 'Das Rennen wurde beendet.');
        } catch (\LogicException $e) {
            $this->addFlash('danger', $e->getMessage());
        }
        return $this->redirectToRoute('race_settings', ['id' => $race->id]);
    }

    #[Route('/{id}', name: 'race_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Race $race): Response
    {
        $this->em->remove($race);
        $this->em->flush();
        $this->addFlash('success', 'Rennen wurde gelöscht.');
        return $this->redirectToRoute('race_index');
    }

    private function generateSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[äÄ]/', 'ae', $slug);
        $slug = preg_replace('/[öÖ]/', 'oe', $slug);
        $slug = preg_replace('/[üÜ]/', 'ue', $slug);
        $slug = preg_replace('/[ß]/', 'ss', $slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-') ?: uniqid('race-');
    }
}
