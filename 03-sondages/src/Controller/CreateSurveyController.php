<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Form\SurveyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

class CreateSurveyController
{
    private Environment $twig;
    private ValidatorInterface $validator;
    private EntityManagerInterface $em;
    private FormFactoryInterface $factory;

    public function __construct(Environment $twig, ValidatorInterface $validator, EntityManagerInterface $em, FormFactoryInterface $factory)
    {
        $this->twig = $twig;
        $this->validator = $validator;
        $this->em = $em;
        $this->factory = $factory;
    }

    public function showForm(): Response
    {
        $html = $this->twig->render('survey/create.html.twig');

        return new Response($html);
    }

    public function save(Request $request)
    {
        dd($request);
        // $builder = $this->factory->createNamedBuilder("", SurveyType::class);
        // $form = $builder->getForm();
        $form = $this->factory->createNamed('', SurveyType::class);

        // 1. Récupérer les données du formulaire à partir de la Requête HTTP
        // ($_POST // Request)
        $form->handleRequest($request);

        // ['question' => ..., 'duration' => ..]

        // 2. Valider que les données soient pas dégueue
        // - question (PasVide / Min10Caracteres)
        // - duration (Obligatoirement 60, 120, ou 300)
        if (!$form->isValid()) {
            dd($form->getErrors(true));
        }

        // 3. Sauvegarde le survey (créer une entité et persister / flusher avec le Manager de Doctrine)
        $survey = $form->getData();

        $this->em->persist($survey);
        $this->em->flush();

        // 4. Rediriger vers la page du survey /identifiant-du-survey
        return new RedirectResponse("/" . $survey->getId());
    }
}
