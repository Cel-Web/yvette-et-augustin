<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produits", name="produit_index")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index()
    {
        return $this->render('produit/index.html.twig');
    }

    /**
     * Permet d'ajouter des produits dans la base de données
     * 
     * @Route("/produits/new", name="produit_new")
     * @Security("is_granted('ROLE_USER')")
     *
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager){

        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $produit->setModeration(false);
            $manager->persist($produit);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le produit <b>{$produit->getNom()}</b> a bien été enregistré !"
            );
        }

        return $this->render('produit/new.html.twig', [
            'form' => $form->createView()
        ]);

    }


}
