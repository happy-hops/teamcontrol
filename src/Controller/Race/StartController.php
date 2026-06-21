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
final class StartController extends AbstractController
{
    public function __construct(
        private readonly RaceRepository        $races,
        private readonly EntityManagerInterface $em,
    ) {}

    #[Route('/{id}/start', name: 'race_start', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function __invoke(Race $race): Response
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
}
