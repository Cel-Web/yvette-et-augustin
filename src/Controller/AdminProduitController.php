<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\ProduitRecette;
use App\Form\ProduitType;
use App\Form\AdminProduitType;
use App\Repository\ProduitRepository;
use App\Repository\TypeProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminProduitController extends AbstractController
{
    /**
     * @Route("/admin/produit", name="admin_produit_index")
     */
    public function index()
    {
        return $this->render('admin/produit/index.html.twig', [
            'controller_name' => 'AdminProduitController',
        ]);
    }

    /**
     * @Route("/admin/produit/modification", name="admin_produit_modification")
     */
    public function modification(ProduitRepository $repoProduit, TypeProduitRepository $repoType ,Request $request, EntityManagerInterface $manager)
    {   
        // repo des recettes en attente de modération
        $AllProduits = $repoProduit->findAll();
        // toutes les catégories de recette
        $AllTypes = $repoType->findAll();
    
        return $this->render('admin/produit/modification.html.twig', [
            'AllProduits' => $AllProduits,
            'AllTypes' => $AllTypes
        ]);
    }


    /**
     * @Route("/admin/produit/moderation", name="admin_produit_moderation")
     */
    public function moderation(ProduitRepository $repoProduit, TypeProduitRepository $repoType ,Request $request, EntityManagerInterface $manager)
    {   
        // repo des produits en attente de modération
        $AllProduitsFalse = $repoProduit->findProduitFalse();
        // tous les types de produits
        $AllTypes = $repoType->findAll();

        // si la requete n'est pas vide (donnc formulaire envoyé)
        if($request->request->count() > 0){

            foreach($AllProduitsFalse as $OneProduit){
                // le produit est le produit en cours
                $produit = $OneProduit;
                $type = $repoType->findOneById($request->request->get('type' . $OneProduit->getId()));
                dump($request->request->get('moderation' . $OneProduit->getId()));
                
                // modification du produit
                $produit->setNom($request->request->get('nom' . $OneProduit->getId()))
                        ->setLatin($request->request->get('latin' . $OneProduit->getId()))
                        ->setType($type)
                        ->setModeration($request->request->get('moderation' . $OneProduit->getId()));
                // enregistrement avant l'envoi en BDD
                $manager->persist($produit);
               
            }
            // envoie dans la bdd
            $manager->flush();

            $this->addFlash(
                'success',
                'Modération des produits effectuée avec succès !'
            );

            $AllProduitsFalse = $repoProduit->findProduitFalse();

        }
    
        return $this->render('admin/produit/moderation.html.twig', [
            'AllProduitsFalse' => $AllProduitsFalse,
            'AllTypes' => $AllTypes
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/admin/produit/{id}/edit", name="admin_produit_edit")
     * 
     * @return Response
     */
    public function edit(Produit $produit, Request $request, EntityManagerInterface $manager){

        // création d'un formulaire
        $form = $this->createForm(AdminProduitType::class, $produit);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            
            // Ajout des informations sur la recette
            $manager->persist($produit);
            // Enregistrement en BDD
            $manager->flush();  

            $this->addFlash(
                'success',
                "Les modifications <strong>{$produit->getNom()}</strong> ont bien été enregistré !"
            );

            return $this->redirectToRoute('admin_produit_edit', [
                'id' => $produit->getId()
            ]);
        }

        return $this->render('admin/produit/edit.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit
        ]);
    }

    /**
     * Permet de supprimer un produit
     * 
     * @Route("/admin/produit/{id}/delete", name="admin_produit_delete")
     *
     * @param Produit $produit
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Produit $produit, EntityManagerInterface $manager){

        $manager->remove($produit);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'annonce <strong>{$produit->getNom()}</strong> a bien été supprimé."
        );
        

        return $this->redirectToRoute("admin_produit_modification");
    }
}
