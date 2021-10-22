<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Repository\ReponseRepository;
use App\Repository\VoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VoteController
{
    private ReponseRepository $reponseRepository;
    private EntityManagerInterface $em;
    private VoteRepository $voteRepository;

    public function __construct(ReponseRepository $reponseRepository, EntityManagerInterface $em, VoteRepository $voteRepository)
    {
        $this->reponseRepository = $reponseRepository;
        $this->em = $em;
        $this->voteRepository = $voteRepository;
    }

    // /survey_id/vote/reponse_id
    public function vote(Request $request)
    {
        // 1. Récupérer le parametre de l'URL reponse_id (Request)
        $id = $request->attributes->getInt('reponse_id');

        // 2. Récupérer la réponse (ReponseRepository)
        $reponse = $this->reponseRepository->find($id);
        // 2.Bonus : Péter une erreur si la réponse n'existe pas
        if (!$reponse) {
            throw new NotFoundHttpException("Cette réponse n'existe pas");
        }

        // ALGO DE VERIFICATION
        $votes = $this->voteRepository->countVotesFromIpForSurvey($request->getClientIp(), $reponse->getSurvey());

        if ($votes > 0) {
            throw new AccessDeniedHttpException("Vous avez déjà voté enfoiré.e");
        }

        // 3. Créer une entité Vote et renseigner la réponse
        $vote = new Vote;
        $vote->setReponse($reponse)
            ->setIp($request->getClientIp());

        // 4. Persister avec le Manager de Doctrine (EntityManagerInterface)
        $this->em->persist($vote);
        $this->em->flush();

        // 5. Redirection vers le sondage (RedirectResponse)
        return new RedirectResponse("/" . $reponse->getSurvey()->getId());
    }
}
