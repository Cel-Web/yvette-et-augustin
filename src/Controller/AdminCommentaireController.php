<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\AdminCommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentaireController extends AbstractController
{
    /**
     * @Route("/admin/commentaire", name="admin_commentaire_index")
     */
    public function index(CommentaireRepository $repoCommentaire)
    {   
        $AllCommentaires = $repoCommentaire->findAll();
        
        return $this->render('admin/commentaire/index.html.twig', [
            'AllCommentaires' => $AllCommentaires,
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/admin/commentaire/{id}/edit", name="admin_commentaire_edit")
     * 
     * @return Response
     */
    public function edit(Commentaire $commentaire, Request $request, EntityManagerInterface $manager){

        // création d'un formulaire
        $form = $this->createForm(AdminCommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Ajout des informations sur le commentaire
            $commentaire->setUser($commentaire->getUser());
            $manager->persist($commentaire);
            // Enregistrement en BDD
            $manager->flush();  

            $this->addFlash(
                'success',
                "Les modifications \"<strong>{$commentaire->getCommentaire()}</strong>\" ont bien été enregistré !"
            );

            return $this->redirectToRoute('admin_commentaire_edit', [
                'id' => $commentaire->getId()
            ]);
        }


        return $this->render('admin/commentaire/edit.html.twig', [
            'form' => $form->createView(),
            'commentaire' => $commentaire
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/admin/commentaire/{id}/delete", name="admin_commentaire_delete")
     *
     * @param Commentaire $commentaire
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Commentaire $commentaire, EntityManagerInterface $manager){

        $manager->remove($commentaire);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire \"<strong>{$commentaire->getCommentaire()}</strong>\" a bien été supprimé."
        );
        

        return $this->redirectToRoute("admin_commentaire_index");
    }
}
