<?php declare(strict_types=1);

namespace App\Controller\Driver;

use App\Form\DriverFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/drivers/new', name: 'driver_new', methods: ['GET'], priority: 1)]
#[IsGranted('ROLE_ADMIN')]
final class NewController extends AbstractController
{
    public function __invoke(): Response
    {
        $form = $this->createForm(DriverFormType::class);

        return $this->render('driver/new.html.twig', [
            'form' => $form,
        ]);
    }
}
