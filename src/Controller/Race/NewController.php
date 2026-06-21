<?php declare(strict_types=1);

namespace App\Controller\Race;

use App\Form\RaceFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/races/new', name: 'race_new', methods: ['GET'], priority: 1)]
#[IsGranted('ROLE_ADMIN')]
final class NewController extends AbstractController
{
    public function __invoke(): Response
    {
        $form = $this->createForm(RaceFormType::class);

        return $this->render('race/new.html.twig', ['form' => $form]);
    }
}
