<?php

namespace App\Controller;

use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TrackRepository $trackRepository): Response
    {
        $tracks = $trackRepository->findAll();

        return $this->render('home/index.html.twig', [
            'tracks' => $tracks,
        ]);
    }

    /**
     * @Route("/tracks", name="tracks")
     */
    public function tracks(TrackRepository $trackRepository): Response
    {
        $tracks = $trackRepository->findAll();

        return $this->render('home/tracks.html.twig', [
            'tracks' => $tracks
        ]);
    }

    /**
     * @Route("/ata", name="atamatoki")
     */
    public function ata(): Response
    {
        return $this->render('home/ata.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('$0.html.twig', []);
    }
}
