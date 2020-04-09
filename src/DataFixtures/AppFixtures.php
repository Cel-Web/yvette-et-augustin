<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Recette;
use App\Entity\Categorie;
use Cocur\Slugify\Slugify;
use App\Entity\Aromatheque;
use App\Entity\Commentaire;
use App\Entity\TypeProduit;
use App\Entity\ProduitRecette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {   
        $faker = Factory::create('fr-FR');
        $slugify = new Slugify();


        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();

        $adminUser->setPrenom('Célia')
            ->setEmail('celia.macabiau@gmail.com')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->addUserRole($adminRole);
        
        $manager->persist($adminUser);

        

        
        // Gestion des users
        $users = [];
        for($i = 1; $i <= 10; $i++){

            $user = new User();

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setPrenom($faker->firstName)
                ->setEmail($faker->email)
                ->setHash($hash);

            $manager->persist($user);
            $users[] = $user;
        }

        // Gestion des types de produit
        $typesProduits = [];
        for($i = 1; $i <= 10; $i++){
            $typeProduit = new TypeProduit();

            $typeProduit->setLibelle($faker->word());

            $manager->persist($typeProduit);
            $typesProduits[] = $typeProduit;
        }

        
        // Gestion des catégories de recette
        $categoriesRecettes = [];
        for($i = 1; $i <= 10; $i++){
            $categorieRecette = new Categorie();

            $categorieRecette->setLibelle($faker->word());

            $manager->persist($categorieRecette);
            $categoriesRecettes[] = $categorieRecette;
        }


        // Gestion des produits
        $produits = [];
        for($i = 1; $i <= 30; $i++){
            $produit = new Produit();

            $typeProduit = $typesProduits[mt_rand(0, count($typesProduits) -1)];

            $produit->setNom($faker->word())
                ->setLatin($faker->word())
                ->setType($typeProduit);

            $produits[] = $produit;
    
            $manager->persist($produit);
        }

        // Gestion des aromathèques
        for($i = 1; $i <= 30; $i++){
            $aromatheque = new Aromatheque();

            $produit = $produits[mt_rand(0, count($produits) -1)];
            $user = $users[mt_rand(0, count($users) -1)];

            $aromatheque->setQuantite($faker->numberBetween($min = 1, $max = 15))
                ->setProduit($produit)
                ->setUser($user);

            $manager->persist($aromatheque);
        }

        // Gestion des recettes
        $recettes = [];
        for($i = 1; $i <= 30; $i++){
            $recette = new Recette();

            $categorie = $categoriesRecettes[mt_rand(0, count($categoriesRecettes) -1)];
            $user = $users[mt_rand(0, count($users) -1)];

            $title = $faker->sentence($nbWords = 6, $variableNbWords = true);
            $slug = $slugify->slugify($title);

            $recette->setTitre($title)
                ->setCategorie($categorie)
                ->setDescription($faker->paragraph($nbSentences = 3, $variableNbSentences = true))
                ->setPubliePar($user)
                ->setSlug($slug)
            ;

            $recettes[] = $recette;

            $manager->persist($recette);

            // gestion des commentaires des recettes
            $commentaire = new Commentaire();

      
            $commentaire->setNote(mt_rand(0, 5))
                    ->setCommentaire($faker->paragraph())
                    ->setUser($user)
                    ->setRecette($recette);

                $manager->persist($commentaire);
            

        }


        // Gestion des produits de recette
        for($i = 1; $i <= 30; $i++){
            $produitRecette = new ProduitRecette();

            $recette = $recettes[mt_rand(0, count($recettes) -1)];
            $produit = $produits[mt_rand(0, count($produits) -1)];
            $user = $users[mt_rand(0, count($users) -1)];

            $produitRecette->setProduit($produit)
                ->setRecette($recette)
                ->setQuantite($faker->numberBetween($min = 1, $max = 15));

            $manager->persist($produitRecette);
        }

        $manager->flush();
    }
}
