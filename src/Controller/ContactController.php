<?php


namespace App\Controller;


use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request)
    {
        $contactForm = $this->createForm(ContactType::class);

        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {

            var_dump($contactForm->getData());
            exit();

            // TODO: envoyer un email / créer un ticket / ...
        }

        return $this->render('contact/contact.html.twig', [
            'contactForm' => $contactForm->createView()
        ]);
    }
}