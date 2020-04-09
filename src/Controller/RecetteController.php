<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Recette;
use App\Entity\Categorie;
use App\Form\RecetteType;
use App\Entity\Aromatheque;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Entity\ProduitRecette;
use App\Repository\RecetteRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AromathequeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class RecetteController extends AbstractController
{
    /**
     * @Route("/recettes", name="recette_all")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(RecetteRepository $repo, CategorieRepository $repoCategorie)
    {
        $categories = $repoCategorie->findAll();
        $recettes = $repo->findAll();

        return $this->render('recette/index.html.twig', [
            'recettes' => $recettes,
            'categories' => $categories,
        ]);
    }

    /**
     * Permet d'ajouter une recette
     * 
     * @Route("/recettes/addRecette", name="recette_add")
     * @Security("is_granted('ROLE_USER')")
     *
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $manager){

        $recette = new Recette();

        $form = $this->createForm(RecetteType::class, $recette);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // Ajout des produits dans la recette        
            foreach($recette->getProduitRecettes() as $produit){
                $produit->setRecette($recette);
                $manager->persist($produit);
            }
            // Ajout des informations sur la recette
            $recette->setPubliePar($this->getUser());
            $recette->setModeration(false);
            $manager->persist($recette);
            // Enregistrement en BDD
            $manager->flush();

            // Flash info
            $this->addFlash(
                'success',
                "La recette a été enregistré ! Elle sera publiée après la publication de nos experts <i class='fas fa-smile'></i>"
            );

            return $this->redirectToRoute('recette_all');
        }
        
        // penser à récupérer l'id de la recette pour les produits de la recette
        return $this->render('recette/add.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * Affiche les recettes d'un utilisateur
     * 
     * @Route("/recettes/user/", name="recette_user")
     * @Security("is_granted('ROLE_USER')")
     */
    public function showRecettesUser(RecetteRepository $repo, CategorieRepository $repoCategorie)
    {   
        $recettes = $repo->findByPubliePar($this->getUser());

        return $this->render('recette/recette_user.html.twig', [
            'recettes' => $recettes
        ]);
    }

    /**
     * Permet d'afficher les recettes
     * 
     * @Route("/recettes/{libelle}", name="recette_category")
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function category($libelle, RecetteRepository $repoRecette, CategorieRepository $repoCategorie)
    {   

        $categories = $repoCategorie->findAll();

        $recettes = $repoRecette->findBy(
            ['categorie' => $repoCategorie->findOneByLibelle($libelle)->getId() ]
        );

        return $this->render('recette/index.html.twig', [
            'recettes' => $recettes,
            'categories' => $categories
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/recettes/{slug}/edit", name="recette_edit")
     * @Security("is_granted('ROLE_USER') and user === recette.getPubliePar()", message="Cette recette ne vous apaprtient pas, vous ne pouvez pas la modifier")
     * 
     * @return Response
     */
    public function edit(Recette $recette, Request $request, EntityManagerInterface $manager){

        // création d'un formulaire
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // Ajout des produits dans la recette        
            foreach($recette->getProduitRecettes() as $produit){
                $produit->setRecette($recette);
                $manager->persist($produit);
            }
            // Ajout des informations sur la recette
            $recette->setPubliePar($this->getUser());
            $manager->persist($recette);
            // Enregistrement en BDD
            $manager->flush();  

            $this->addFlash(
                'success',
                "Les modifications <strong>{$recette->getTitre()}</strong> ont bien été enregistré !"
            );

            return $this->redirectToRoute('recette_edit', [
                'slug' => $recette->getSlug()
            ]);
        }


        return $this->render('recette/edit.html.twig', [
            'form' => $form->createView(),
            'recette' => $recette
        ]);
    }

    /**
     * Permet d'afficher une recette
     * 
     * @Route("/recettes/{libelle}/{slug}", name="recette_show")
     * @Security("is_granted('ROLE_USER')")
     *
     * @return Response
     */
    public function show($libelle, $slug, RecetteRepository $repo, CategorieRepository $repoCategorie, AromathequeRepository $repoAromatheque, Request $request, EntityManagerInterface $manager){

        // comparer les produits pour affichage d'un message.
        $produitsByUser = $repoAromatheque->findByUser($this->getUser());
        $produitsByRecette = $repo->findBySlug($slug);

        
        $AllProduitsByUser = [];
        foreach($produitsByUser as $one){
            array_push($AllProduitsByUser, $one->getProduit()->getId());
        }

        $cptRecette = 0;
        $AllProduitsByRecette = [];
        foreach($produitsByRecette as $one){
            foreach($one->getProduitRecettes() as $one2){
                array_push($AllProduitsByRecette, $one2->getProduit()->getId());
                $cptRecette++;
            }
        }

        $result = array_intersect($AllProduitsByRecette, $AllProduitsByUser);

        $cpt = 0;
        foreach($result as $res){
            $cpt++;
        }

        if($cpt == $cptRecette){
            // Flash info
            $this->addFlash(
                'success',
                "<b> Super !</b> Vous avez tous les ingrédients pour effectuer cette recette !"
            );
        }elseif($cpt < $cptRecette){
            // Flash info
            $this->addFlash(
                'warning',
                "<b> Dommage !</b> Vous avez $cpt produit(s) sur $cptRecette pour réaliser cette recette."
            );
        }
        // fin affichage message.


        // récupération de la catégorie
        $libelle = $repoCategorie->findOneByLibelle($libelle);
        $categories = $repoCategorie->findAll();
        // récupération de l'annonce qui correspond au slug
        $recette = $repo->findOneBySlug($slug);

        // Gestion du formulaire
        $commentaire = new Commentaire();

        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Ajout des informations sur la recette
            $commentaire->setUser($this->getUser())
                ->setRecette($repo->findOneBySlug($slug));

            $manager->persist($commentaire);
            // Enregistrement en BDD
            $manager->flush();

            // Flash info
            $this->addFlash(
                'success',
                "Votre commenraire a bien été enregistré, merci pour cet avis !"
            );
        }

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'categories' => $categories,
            'form' => $form->createView()
        ]); 
    }
}
