<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\PostRepository;
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
    

    #[Route('/post/{slug}', name: 'app_show', methods: ['GET'])]
    #[ParamConverter('post', class: 'App\Entity\Post')]
    public function show(Post $post, PostRepository $postRepository): Response
    {
      

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

}
