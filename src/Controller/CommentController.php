<?php 

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CommentController extends AbstractController
{
    #[Route('/comment/{id}', name: 'app_delete')]
    #[Security('is_granted("ROLE_USER") and user === comment.getAuthor()')]
    public function delete(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        $param = ['slug' =>$comment->getPost()->getSlug() ];


        if($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))){
            $em->remove($comment);
            $em->flush();

            $this->addFlash('success', 'Commentaire supprimé avec succès');
        }
   
        return $this->redirectToRoute('app_show', $param);
    }
}