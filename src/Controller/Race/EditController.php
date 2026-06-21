<?php declare(strict_types=1);

namespace App\Controller\Race;

use App\Entity\Race;
use App\Form\RaceFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{id}/edit', name: 'race_edit', requirements: ['id' => '\d+'], methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class EditController extends AbstractController
{
    public function __invoke(Race $race): Response
    {
        $form = $this->createForm(RaceFormType::class, $race);

        return $this->render('race/edit.html.twig', ['race' => $race, 'form' => $form]);
    }
}
