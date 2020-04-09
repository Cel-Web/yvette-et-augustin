<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Aromatheque;
use App\Form\AromathequeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AromathequeController extends AbstractController
{
    /**
     * Permet d'afficher l'aromathèque d'un utilisateur
     * 
     * @Route("/aromatheque", name="aromatheque")
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function show()
    {
        return $this->render('aromatheque/show.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * Permet d'ajouter des huiles à l'aromathèque d'une personne
     *
     * @Route("/addAromatheque", name="aromatheque_add")
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function addAromatheque(Request $request, EntityManagerInterface $manager){

        $aromatheque = new Aromatheque();
        // formulaire 
        $form = $this->createForm(AromathequeType::class, $aromatheque);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $aromatheque->setUser($this->getUser());
            $manager->persist($aromatheque);
            $manager->flush();
            dump($aromatheque);
            $this->addFlash(
                'success',
                "Le produit a bien été ajouté à votre aromathèque !"
            );
        }

        return $this->render('aromatheque/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
