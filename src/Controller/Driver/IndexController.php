<?php declare(strict_types=1);

namespace App\Controller\Driver;

use App\Repository\DriverRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/drivers', name: 'driver_index', methods: ['GET'])]
#[IsGranted('ROLE_ADMIN')]
final class IndexController extends AbstractController
{
    public function __invoke(
        DriverRepository $drivers,
        Request          $request
    ): Response
    {
        $search  = $request->query->getString('q');
        $drivers = $search
            ? $drivers->search($search)
            : $drivers->findAllOrderedByName();

        return $this->render('driver/index.html.twig', [
            'drivers' => $drivers,
            'search'  => $search,
        ]);
    }
}
