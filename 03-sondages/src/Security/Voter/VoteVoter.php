<?php

namespace App\Security\Voter;

use App\Entity\Survey;
use App\Repository\VoteRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class VoteVoter extends Voter
{
    private RequestStack $requestStack;
    private VoteRepository $voteRepository;

    public function __construct(RequestStack $requestStack, VoteRepository $voteRepository)
    {
        $this->requestStack = $requestStack;
        $this->voteRepository = $voteRepository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === "CAN_VOTE" && $subject instanceof Survey;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $request = $this->requestStack->getCurrentRequest();

        $votes = $this->voteRepository->countVotesFromIpForSurvey($request->getClientIp(), $subject);

        return $votes === 0;
    }
}
