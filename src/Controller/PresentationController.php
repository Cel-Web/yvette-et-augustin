<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PresentationController extends AbstractController
{
    /**
     * @Route("/qui-sommes-nous", name="presentation")
     */
    public function index()
    {
        return $this->render('presentation/index.html.twig');
    }
}
