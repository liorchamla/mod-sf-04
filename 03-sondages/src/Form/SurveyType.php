<?php

namespace App\Form;

use App\Entity\Reponse;
use App\Entity\Survey;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ])
            ->add('reponses', CollectionType::class, [
                'entry_type' => ReponseType::class,
                'allow_add' => true
            ]);

        // $builder->get('reponses')
        //     ->addModelTransformer(new CallbackTransformer(function ($value) {
        //         // dd("Transform :", $value);
        //     }, function ($reponses) {
        //         $entites = [];

        //         foreach ($reponses as $text) {
        //             $reponse = new Reponse;
        //             $reponse->setText($text);

        //             $entites[] = $reponse;
        //         }

        //         return $entites;
        //     }));
        // ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
        //     $data = $event->getData();

        //     $reponses = $data['reponses'];

        //     $reponsesEntities = [];

        //     foreach ($reponses as $text) {
        //         $reponse = new Reponse;
        //         $reponse->setText($text);

        //         $reponsesEntities[] = $reponse;
        //     }

        //     $data['reponses'] = $reponsesEntities;

        //     $event->setData($data);
        // });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Survey::class,
            'csrf_protection' => true,
            'csrf_field_name' => 'csrfToken',
            'csrf_token_id' => 'survey_token'
        ]);
    }
}
