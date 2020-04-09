<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ConfigType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AccountType extends ConfigType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom', TextType::class, $this->getConfiguration("Prénom / Pseudonyme", "Entrez ici votre prénom ou votre pseudonym"))
            ->add('email', EmailType::class, $this->getConfiguration("Adresse e-mail", "Entrez ici votre adresse e-mail"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
