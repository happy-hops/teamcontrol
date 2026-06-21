<?php declare(strict_types=1);

namespace App\Controller\Race;

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
final class UpdateController extends AbstractController
{
    public function __construct(
        private readonly RaceRepository        $races,
        private readonly EntityManagerInterface $em,
    ) {}

    #[Route('/{id}', name: 'race_update', methods: ['POST', 'PUT', 'PATCH'], requirements: ['id' => '\d+'])]
    public function __invoke(Request $request, Race $race): Response
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
}
