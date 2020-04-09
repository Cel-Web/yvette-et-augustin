<?php

namespace App\Form;

use App\Entity\Produit;
use App\Form\ConfigType;
use App\Entity\Aromatheque;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AromathequeType extends ConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder
            ->add('quantite', TextType::class, $this->getConfiguration("Quantité", "Quantité pour la recette"))
            ->add('produit', EntityType::class, [
                'class' => Produit::class, 
                'choice_label' => 'nomType',
                'query_builder' => function(ProduitRepository $repo){
                    return $repo->createQueryBuilder('p')
                                ->where('p.moderation = true')
                                ->orderBy('p.nom', 'ASC');
                }
            ], 
            $this->getConfiguration("Sélectionnez un produit", "Sélectionner un produit"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Aromatheque::class,
        ]);
    }
}
