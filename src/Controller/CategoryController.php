<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'app_category')]
    public function index(Category $category, PostRepository $postRepo, PaginatorInterface $paginator,Request $request): Response
    {
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $searchData->page = $request->query->getInt('page', 1);
            $posts = $postRepository->findBySearch($searchData);
    
            return $this->render('post/index.html.twig', [
                'form' => $form->createView(),
                'posts' => $posts,
                'categ' => $category,
            ]);
        }
      
    
        $page = $request->query->getInt('page', 1);
        $posts = $postRepo->findPublished($page, $category->getId());
      
        return $this->render('category/index.html.twig', [
            'posts' => $posts,
            'categ' => $category,
            'form' => $form->createView(),
        ]);
    }
}
