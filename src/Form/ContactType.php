<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [new NotNull(), new NotBlank(), new Length(['min' => 3])]
            ])
            ->add('email', TextType::class, [
                'constraints' => [new NotNull(), new NotBlank(), new Email()]
            ])
            ->add('subject', TextType::class, [
                'constraints' => [new NotNull(), new NotBlank(), new Length(['min' => 3])]
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [new NotNull(), new NotBlank(), new Length(['min' => 3])]
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
