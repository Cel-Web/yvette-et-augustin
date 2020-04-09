<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Repository\CategorieRepository;
use App\Repository\FavorisRepository;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class FavorisController extends AbstractController
{
    /**
     * @Route("/favoris", name="favoris")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(FavorisRepository $repo)
    {

        $favoris = $repo->findByUser($this->getUser());

        return $this->render('favoris/index.html.twig', [
            'favoris' => $favoris
        ]);
    }


    /**
     * Permet de supprimer un favoris
     * 
     * @Route("/favoris/delete/{id}", name="favoris_delete")
     * @Security("is_granted('ROLE_USER') and user == favoris.getUser()")
     * @ParamConverter("id", options={"id": "id"})
     *
     * @param Favoris
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Favoris $favoris, EntityManagerInterface $manager, Request $request, RecetteRepository $repo){

        // récupération de l'annonce par l'id
        $recette = $repo->findOneById($favoris->getRecette());

        // Suppression de la base de données
        $manager->remove($favoris);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le favoris a bien été retiré de votre liste."
        );

        if($request->server->get('HTTP_REFERER') === "http://localhost:8000/favoris")
        {
            return $this->redirectToRoute('favoris'); 
        }else{
            return $this->redirectToRoute('recette_show', [
                'slug' => $recette->getSlug(),
                'libelle' => $recette->getCategorie()->getLibelle()
            ]); 
        }
    }


    /**
     * Permet de supprimer un favoris
     * 
     * @Route("/favoris/add/{idRecette}/{idUser}", name="favoris_add")
     * @Security("is_granted('ROLE_USER')")
     *
     * @param ObjectManager $manager
     * @param Request
     * @return Response
     */
    public function add(Request $request, EntityManagerInterface $manager, RecetteRepository $repo){
  
        $favoris = new Favoris();

        // récupération de l'annonce par l'id
        $recette = $repo->findOneById($request->attributes->get('idRecette'));

        $favoris->setRecette($recette)
            ->setUser($this->getUser());

        $manager->persist($favoris);
        $manager->flush();


        $this->addFlash(
            'success',
            "Le favoris a bien été retiré de votre liste."
        );

        return $this->redirectToRoute('recette_show', [
            'slug' => $recette->getSlug(),
            'libelle' => $recette->getCategorie()->getLibelle()
        ]); 
    }



}
