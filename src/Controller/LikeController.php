<?php 

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class LikeController extends AbstractController 
{
    #[Route('/like/{id}', name: 'like_post')]
    #[IsGranted('ROLE_USER')]
    public function like(Post $post, EntityManagerInterface $manager): Response
    {

        $user = $this->getUser();
        
        if($post->isLikedByUser($user)){
            $post->removeLikes($user);
            $manager -> flush();

            return $this->json([
                // 'likeCount' => $post->getLikes()->count()
                'likeCount' => $post->getLikeCount()

            ]);
        }

        $post->addLikes($user);
        $manager -> flush();


        return $this->json([
            'likeCount' => $post->getLikeCount()
        ]);
    }
}