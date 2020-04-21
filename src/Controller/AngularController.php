<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AngularController extends AbstractController
{

    /**
     * @Route("/")
     */
    public function getDynamicSinglePageApplication()
    {
        return $this->render('app/app.html.twig');
    }
}