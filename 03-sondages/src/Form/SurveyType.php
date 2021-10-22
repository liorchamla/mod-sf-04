<?php

namespace App\Form;

use App\Entity\Survey;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SurveyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', TextType::class, [
                'constraints' => [
                    new NotBlank(["message" => "La question ne doit pas être vide"]),
                    new Length(["min" => 10, "minMessage" => "La question doit faire 10 caractères au moins"])
                ]
            ])
            ->add('duration', IntegerType::class, [
                'constraints' => new Choice([
                    'choices' => [60, 120, 300],
                    'message' => "La durée doit être de 1, 2 ou 5 minutes"
                ])
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Survey::class,
        ]);
    }
}
