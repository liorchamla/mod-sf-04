<?php

namespace App\Controller;

use App\Repository\SurveyRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class SurveyApiController
{
    private SurveyRepository $surveyRepository;
    private Security $security;
    private SerializerInterface $serializer;

    public function __construct(SurveyRepository $surveyRepository, Security $security, SerializerInterface $serializer)
    {
        $this->surveyRepository = $surveyRepository;
        $this->security = $security;
        $this->serializer = $serializer;
    }

    // /api/survey/12
    public function getSurveyInfos(Request $request)
    {
        $id = $request->attributes->getInt('id');

        $survey = $this->surveyRepository->find($id);

        $data = [
            'reponses' => $survey->getReponses(),
            'canVote' => $this->security->isGranted('CAN_VOTE', $survey)
        ];

        // $json = json_encode($data);
        $json = $this->serializer->serialize($data, 'json', [
            'groups' => "reponse"
        ]);

        // return new Response($json, 200, [
        //     'Content-Type' => 'application/json'
        // ]);
        return new JsonResponse($json, 200, [], true);
        // JSON
        /**
         * {
         *  reponses: [
         *      {id: 12, text: 'Bullit'};
         *      {id: 13, text: 'Ripper'}
         *  ],
         *  canVote: true
         * }
         */
    }
}
