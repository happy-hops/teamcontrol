<?php declare(strict_types=1);

namespace App\Controller\Station;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/stations')]
#[IsGranted('ROLE_ADMIN')]
final class StationController extends AbstractController
{
    #[Route('', name: 'admin_station_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/station/index.html.twig');
    }
}
