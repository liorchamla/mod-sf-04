<?php

namespace App\Controller;

use App\Repository\SurveyRepository;
use App\Repository\VoteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class ShowSurveyController
{
    private SurveyRepository $surveyRepository;
    private Environment $twig;
    private VoteRepository $voteRepository;

    public function __construct(SurveyRepository $surveyRepository, Environment $twig, VoteRepository $voteRepository)
    {
        $this->surveyRepository = $surveyRepository;
        $this->twig = $twig;
        $this->voteRepository = $voteRepository;
    }

    public function show(Request $request)
    {
        // 1. Récupérer l'identifiant (l'URL DONC la Request)
        $id = $request->attributes->getInt('id');

        // 2. Récupérer l'entité correspondante (SurveyRepository)
        $survey = $this->surveyRepository->find($id);

        if (!$survey) {
            throw new NotFoundHttpException("Ce sondage n'existe pas !");
        }

        $votes = $this->voteRepository->countVotesFromIpForSurvey($request->getClientIp(), $survey);

        // 3. Afficher dans un template HTML (Twig\Environment) le Survey
        $html = $this->twig->render('survey/show.html.twig', [
            'survey' => $survey,
            'hasAlreadyVoted' => $votes > 0
        ]);

        // 4. Retourner une réponse qui contient le template (Response)
        return new Response($html);
    }
}
