<?php

namespace App\Form;

use App\Entity\Produit;
use App\Form\ConfigType;
use App\Entity\TypeProduit;
use App\Repository\TypeProduitRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminProduitType extends ConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, $this->getConfiguration("Nom du produit", "Nom complet du produit - HE /  HV / Hy etc."))
            ->add('latin', TextType::class, $this->getConfiguration("Nom latin", "Nom latin du produit - HE /  HV / Hy etc."))
            ->add('type', EntityType::class, [
                'class' => TypeProduit::class, 
                'choice_label' => 'libelle',
                'query_builder' => function(TypeProduitRepository $repo){
                    return $repo->createQueryBuilder('t')
                                ->orderBy('t.libelle', 'ASC');
                }
            ], 
            $this->getConfiguration("Sélectionnez une catégorie", "Sélectionner un catégorie"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
