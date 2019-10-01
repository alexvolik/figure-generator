<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function main()
    {
        return $this->render('pages/main.html.twig');
    }
}