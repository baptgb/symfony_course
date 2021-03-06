<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @param Request $request
     * @Route("/add_post/{user_id}", name="post_add")
     */
    public function addPost(Request $request, $user_id)
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepository->findOneBy(['id' => $user_id]);
        if ($request->request->has('title') && $request->request->has('content')) {
            $post = new Post();
            $post->setContent($request->request->get('content'));
            $post->setTitle($request->request->get('title'));
            $post->setCreatedAt(new \DateTime());
            $post->setUser($user);

            $this->getDoctrine()->getManager()->persist($post);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('post/add.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @Route("/post/{id}", name="post_show")
     */
    public function showPost(Request $request, $id)
    {
        $postRepository = $this->getDoctrine()->getRepository(Post::class);
        $post = $postRepository->findOneBy(['id' => $id]);

        $otherPosts = $postRepository->getOtherPosts($id, $post->getUser());

        $user = $this->getUser();
        if ($request->request->has('content') && $user instanceof User) {
            $comment = new Comment();
            $comment->setContent($request->request->get('content'));
            $comment->setCreatedAt(new \DateTime());
            $comment->setPost($post);
            $comment->setUser($user);

            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
        }

        $commentRepository = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $commentRepository->findBy(['post' => $id], ['createdAt' => 'DESC']);

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'otherPosts' => $otherPosts,
            'comments' => $comments,
        ]);
    }
}