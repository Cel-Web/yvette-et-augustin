<?php

namespace App\Form;

use App\Entity\Recette;
use App\Form\ConfigType;
use App\Entity\Categorie;
use App\Form\ProduitRecetteType;
use Faker\Provider\ar_JO\Text;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminRecetteType extends ConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('moderation', ChoiceType::class, $this->getConfiguration("Valider la recette ?", ""))
            ->add('titre', TextType::class, $this->getConfiguration("Titre de la recette", "Précisez votre recette"))
            ->add('slug', TextType::class, $this->getConfiguration("Modifier l'Url de la recette", "Modifier l'Url de la recette seulement si nécessaire"))
            ->add('description', TextareaType::class, $this->getConfiguration("Description", "Détaillez ici votre recette, étape par étape"))
            ->add('categorie', EntityType::class, ['class' => Categorie::class, 'choice_label' => 'libelle'], $this->getConfiguration("Sélectionnez une catégorie", "Sélectionner un produit"))
            ->add('ProduitRecettes', CollectionType::class,
                    [
                        'entry_type' => ProduitRecetteType::class,
                        'allow_add' => true,
                        'allow_delete' => true,
                    ]
                )
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
