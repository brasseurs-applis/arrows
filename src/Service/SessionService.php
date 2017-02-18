<?php

namespace BrasseursApplis\Arrows\Service;

use BrasseursApplis\Arrows\Id\ResearcherId;
use BrasseursApplis\Arrows\Id\ScenarioTemplateId;
use BrasseursApplis\Arrows\Id\SessionId;
use BrasseursApplis\Arrows\Id\SubjectId;
use BrasseursApplis\Arrows\Repository\ScenarioTemplateRepository;
use BrasseursApplis\Arrows\Repository\SessionRepository;
use BrasseursApplis\Arrows\Session;
use BrasseursApplis\Arrows\VO\SubjectsCouple;

class SessionService
{
    /** @var SessionRepository */
    private $sessionRepository;

    /** @var ScenarioTemplateRepository */
    private $scenarioTemplateRepository;

    /**
     * SessionService constructor.
     *
     * @param SessionRepository          $sessionRepository
     * @param ScenarioTemplateRepository $scenarioTemplateRepository
     */
    public function __construct(
        SessionRepository $sessionRepository,
        ScenarioTemplateRepository $scenarioTemplateRepository
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->scenarioTemplateRepository = $scenarioTemplateRepository;
    }

    /**
     * @param SessionId          $sessionId
     * @param ResearcherId       $researcher
     * @param SubjectId          $subjectOne
     * @param SubjectId          $subjectTwo
     * @param ScenarioTemplateId $scenarioTemplateId
     */
    public function createSession(
        SessionId $sessionId,
        ResearcherId $researcher,
        SubjectId $subjectOne,
        SubjectId $subjectTwo,
        ScenarioTemplateId $scenarioTemplateId
    ) {
        $scenarioTemplate = $this->scenarioTemplateRepository->get($scenarioTemplateId);

        $session = new Session(
            $sessionId,
            $scenarioTemplateId,
            $scenarioTemplate->getScenario(),
            new SubjectsCouple($subjectOne, $subjectTwo),
            $researcher
        );

        $this->sessionRepository->persist($session);
    }

    /**
     * @param SessionId          $sessionId
     * @param ResearcherId       $researcher
     * @param SubjectId          $subjectOne
     * @param SubjectId          $subjectTwo
     * @param ScenarioTemplateId $scenarioTemplateId
     */
    public function updateSession(
        SessionId $sessionId,
        ResearcherId $researcher,
        SubjectId $subjectOne,
        SubjectId $subjectTwo,
        ScenarioTemplateId $scenarioTemplateId
    ) {
        $scenarioTemplate = $this->scenarioTemplateRepository->get($scenarioTemplateId);

        $session = $this->sessionRepository->get($sessionId);

        $session->changeObserver($researcher);
        $session->changeSubjects(new SubjectsCouple($subjectOne, $subjectTwo));
        $session->changeScenario($scenarioTemplateId, $scenarioTemplate->getScenario());

        $this->sessionRepository->persist($session);
    }
}
