<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_list")
     */
    public function users(Request $request)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findAll();
        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $commentsCount = $commentRepository->getCommentsCountGroupByPost();

        return $this->render('user/list.html.twig', [
            'users' => $users,
            'commentsCount' => $commentsCount
        ]);
    }

    /**
     * @Route("/register", name="user_register")
     */
    public function register(Request $request)
    {
        $user = new User();
        $registerForm = $this->createForm(UserType::class, $user);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $user->setCreatedAt(new \DateTime());
            $salt = uniqid();
            $hash = hash('sha256', $user->getPassword() . $salt);
            $user->setPassword($hash);
            $user->setSalt($salt);

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('user/register.html.twig', [
            'registerForm' => $registerForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/profile/{id}", name="user_profile")
     */
    public function profile(Request $request, $id)
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->find($id);

        if ($request->request->has('username') && $request->request->has('firstname') && $request->request->has('lastname')) {
            $user->setUsername($request->request->get('username'));
            $user->setFirstname($request->request->get('firstname'));
            $user->setLastname($request->request->get('lastname'));

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }

        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $postsCount = $postRepository->countPostsByUser($user);

        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $commentsCount = $commentRepository->countCommentsByPostUser($user);

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'postsCount' => $postsCount,
            'commentsCount' => $commentsCount
        ]);
    }
}