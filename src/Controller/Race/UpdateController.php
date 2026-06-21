<?php declare(strict_types=1);

namespace App\Controller\Race;

use App\Entity\Race;
use App\Form\RaceFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/{id}', name: 'race_update', requirements: ['id' => '\d+'], methods: ['POST', 'PUT', 'PATCH'])]
#[IsGranted('ROLE_ADMIN')]
final class UpdateController extends AbstractController
{
    public function __invoke(
        Race $race,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $form = $this->createForm(RaceFormType::class, $race);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Rennen wurde aktualisiert.');

            return $this->redirectToRoute('race_settings', ['id' => $race->id]);
        }

        return $this->render('race/edit.html.twig', ['race' => $race, 'form' => $form]);
    }
}
