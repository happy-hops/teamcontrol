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
final class DeleteController extends AbstractController
{
    public function __construct(
        private readonly RaceRepository        $races,
        private readonly EntityManagerInterface $em,
    ) {}

    #[Route('/{id}', name: 'race_delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function __invoke(Race $race): Response
    {
        $this->em->remove($race);
        $this->em->flush();
        $this->addFlash('success', 'Rennen wurde gelöscht.');
        return $this->redirectToRoute('race_index');
    }
}
