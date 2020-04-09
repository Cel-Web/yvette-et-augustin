<?php

namespace App\Form;

use App\Form\ConfigType;
use App\Entity\Commentaire;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminCommentaireType extends ConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note', IntegerType::class, 
            $this->getConfiguration("Note sur 5", "Veuillez indiquer votre note de 0 à 5", [
                'attr' => [
                    'min' => 0,
                    'max' =>5,
                    'step' => 1
                ]
            ]))
            ->add('commentaire', TextareaType::class, $this->getConfiguration("Commentaire / Avis", "Laissez un avis ou un conseil pour améliorer cette recette !"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
