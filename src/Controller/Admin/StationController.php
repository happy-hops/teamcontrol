<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/stations')]
#[IsGranted('ROLE_ADMIN')]
class StationController extends AbstractController
{
    #[Route('', name: 'admin_station_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/station/index.html.twig');
    }
}
