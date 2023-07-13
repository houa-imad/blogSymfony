<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\SearchType;
use App\Form\CommentType;
use App\Model\SearchData;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;




class PostController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, PostRepository $postRepository, PaginatorInterface $paginator): Response
    {
        
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);

        // Recuprer le parametre result de la requete
        if ( $request->query->has('result')){
            $res = $request->query->get('result');
            $searchData->q = $res;
            $searchData->page = $request->query->getInt('page', 1);
            $posts = $postRepository->findBySearch($searchData);

            return $this->render('post/index.html.twig', [
                'form' => $form->createView(),
                'posts' => $posts,
                'result' => $searchData->q
            ]);

        }
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $posts = $postRepository->findBySearch($searchData);
    
            return $this->render('post/index.html.twig', [
                'form' => $form->createView(),
                'posts' => $posts,
                'result' => $searchData->q
            ]);
        }
    
        $page = $request->query->getInt('page', 1);
        $posts = $postRepository->findPublished($page);
  
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'form' => $form->createView()
        ]);
    }
    

    #[Route('/post/{slug}', name: 'app_show', methods: ['GET', 'POST'])]
    #[ParamConverter('post', class: 'App\Entity\Post')]
    public function show(Post $post, Request $request, EntityManagerInterface $em): Response
    {
      $comment = new Comment();
        $comment->setPost($post);
      if($this->getUser()){
        $comment->setAuthor($this->getUser());
      }

      $form = $this->createForm(CommentType::class, $comment);
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){

        $comment->setIsApproved(0);

        $em->persist($comment);
        $em->flush();

        $this->addFlash('success', 'Votre commentaire a été envoyé avec succès, il sera publié après validation par l\'administrateur');

        return $this->redirectToRoute('app_show', ['slug' => $post->getSlug()]);
      }
      

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

}
