<?php declare(strict_types=1);

namespace App\Controller\Driver;

use App\Entity\Driver;
use App\Form\DriverFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/drivers/{id}/edit', name: 'driver_edit', requirements: ['id' => '\d+'], methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class EditController extends AbstractController
{
    public function __invoke(Driver $driver): Response
    {
        $form = $this->createForm(DriverFormType::class, $driver);

        return $this->render('driver/edit.html.twig', [
            'driver' => $driver,
            'form'   => $form,
        ]);
    }
}
