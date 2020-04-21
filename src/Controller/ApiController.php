<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/users", name="api_users")
     */
    public function users(SerializerInterface $serializer)
    {
        $this->getUser();
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();
        $serializedUsers = $serializer->serialize($users, 'json', ['groups' => ['user']]);
        return new JsonResponse($serializedUsers, 200, [], true);
    }
}