<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $authors = $options['authors'];

        $builder
            ->add('author',ChoiceType::class, [
                'label' => 'Author',
                'choices' => $authors,
                'choice_label' => function ($author) {
                    return "{$author->first_name} {$author->last_name}";
                }
            ])
            ->add('title', TextType::class)
            ->add('release_date', DateType::class)
            ->add('description', TextType::class)
            ->add('isbn', TextType::class)
            ->add('format', TextType::class)
            ->add('number_of_pages', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'authors' => []
        ]);
    }
}
