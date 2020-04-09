<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\AdminRecetteType;
use App\Repository\ProduitRepository;
use App\Repository\RecetteRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminRecetteController extends AbstractController
{
    /**
     * @Route("/admin/recette", name="admin_recette_index")
     */
    public function index()
    {
        return $this->render('admin_recette/index.html.twig', [
            'controller_name' => 'AdminRecetteController',
        ]);
    }

    /**
     * @Route("/admin/recette/moderation", name="admin_recette_moderation")
     */
    public function moderation(RecetteRepository $repoRecette, CategorieRepository $repoCategorie ,Request $request, EntityManagerInterface $manager)
    {   
        // repo des recettes en attente de modération
        $AllRecettesFalse = $repoRecette->findRecetteFalse();
        // toutes les catégories de recette
        $AllCategories = $repoCategorie->findAll();

        // si la requete n'est pas vide (donnc formulaire envoyé)
        if($request->request->count() > 0){

            foreach($AllRecettesFalse as $OneRecette){
                // la recette correspond à la recette en cours (récupérée)
                $recette = $OneRecette;                
                // modification du recette
                $recette->setModeration($request->request->get('moderation' . $OneRecette->getId()));
                // enregistrement avant l'envoi en BDD
                $manager->persist($recette);
               
            }
            // envoie dans la bdd
            $manager->flush();

            $this->addFlash(
                'success',
                'Modération de(s) recette(s) effectuée avec succès !'
            );

            $AllRecettesFalse = $repoRecette->findRecetteFalse();

        }
    
        return $this->render('admin/recette/moderation.html.twig', [
            'AllRecettesFalse' => $AllRecettesFalse,
            'AllCategories' => $AllCategories
        ]);
    }

    /**
     * @Route("/admin/recette/modification", name="admin_recette_modification")
     */
    public function modification(RecetteRepository $repoRecette, CategorieRepository $repoCategorie)
    {   
        // repo des recettes en attente de modération
        $AllRecettes = $repoRecette->findAll();
        // toutes les catégories de recette
        $AllCategories = $repoCategorie->findAll();
    
        return $this->render('admin/recette/modification.html.twig', [
            'AllRecettes' => $AllRecettes,
            'AllCategories' => $AllCategories
        ]);
    }

    /**
     * Permet de supprimer une recette
     * 
     * @Route("/admin/recette/{slug}/delete", name="admin_recette_delete")
     *
     * @param Recette $recette
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Recette $recette, EntityManagerInterface $manager){

        $manager->remove($recette);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$recette->getTitre()}</strong> a bien été supprimé."
        );
        

        return $this->redirectToRoute("admin_recette_modification");
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/admin/recette/{slug}/edit", name="admin_recette_edit")
     * 
     * @return Response
     */
    public function edit(Recette $recette, Request $request, EntityManagerInterface $manager){

        // création d'un formulaire
        $form = $this->createForm(AdminRecetteType::class, $recette);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // Ajout des produits dans la recette        
            foreach($recette->getProduitRecettes() as $produit){
                $produit->setRecette($recette);
                $manager->persist($produit);
            }
            // Ajout des informations sur la recette
            $manager->persist($recette);
            // Enregistrement en BDD
            $manager->flush();  

            $this->addFlash(
                'success',
                "Les modifications <strong>{$recette->getTitre()}</strong> ont bien été enregistré !"
            );

            return $this->redirectToRoute('admin_recette_edit', [
                'slug' => $recette->getSlug()
            ]);
        }


        return $this->render('admin/recette/edit.html.twig', [
            'form' => $form->createView(),
            'recette' => $recette
        ]);
    }
}
