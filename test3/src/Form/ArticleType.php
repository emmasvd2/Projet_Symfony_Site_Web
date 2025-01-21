<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class ArticleType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title')
        ->add('description')
        ->add('price', null, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez entrer un prix',
                ]),
                new Type([
                    'type' => 'numeric',
                    'message' => 'Le prix doit être numérique',
                ]),
            ],
        ]);
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
