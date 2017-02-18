<?php

namespace BrasseursApplis\Arrows\App\Controller\Arrows;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\App\Controller\Util\Paginator;
use BrasseursApplis\Arrows\App\DTO\SessionDTO;
use BrasseursApplis\Arrows\App\Finder\SessionFinder;
use BrasseursApplis\Arrows\App\Form\SessionType;
use BrasseursApplis\Arrows\Id\ResearcherId;
use BrasseursApplis\Arrows\Id\ScenarioTemplateId;
use BrasseursApplis\Arrows\Id\SessionId;
use BrasseursApplis\Arrows\Id\SubjectId;
use BrasseursApplis\Arrows\Service\SessionService;
use Doctrine\ORM\ORMInvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;

class SessionController
{
    /** @var SessionFinder */
    private $sessionFinder;

    /** @var SessionService */
    private $sessionService;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var \Twig_Environment */
    private $twig;

    /** @var UrlGenerator */
    private $urlGenerator;

    /**
     * SessionController constructor.
     *
     * @param SessionFinder        $sessionFinder
     * @param SessionService       $sessionService
     * @param FormFactoryInterface $formFactory
     * @param \Twig_Environment    $twig
     * @param UrlGenerator         $urlGenerator
     */
    public function __construct(
        SessionFinder $sessionFinder,
        SessionService $sessionService,
        FormFactoryInterface $formFactory,
        \Twig_Environment $twig,
        UrlGenerator $urlGenerator
    ) {
        $this->sessionFinder = $sessionFinder;
        $this->sessionService = $sessionService;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return RedirectResponse
     *
     * @throws \InvalidArgumentException
     */
    public function createAction()
    {
        return new RedirectResponse($this->urlGenerator->generate('session_edit', [ 'sessionId' => Uuid::uuid4() ]));
    }

    /**
     * @param Request $request
     * @param string  $sessionId
     *
     * @return Response
     *
     * @throws ORMInvalidArgumentException
     * @throws AssertionFailedException
     * @throws InvalidOptionsException
     * @throws RouteNotFoundException
     * @throws MissingMandatoryParametersException
     * @throws InvalidParameterException
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function editAction(Request $request, $sessionId)
    {
        $session = $this->sessionFinder->find($sessionId);
        $new = false;

        if ($session === null) {
            $session = new SessionDTO($sessionId);
            $new = true;
        }

        $form = $this->formFactory->create(SessionType::class, $session);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || ! $form->isValid()) {
            $response = new Response();
            $response->setContent(
                $this->twig->render(
                    'session/edit.twig',
                    [ 'form' => $form->createView(), 'sessionId' => (string) $sessionId ]
                )
            );

            return $response;
        }

        /** @var SessionDTO $session */
        $session = $form->getData();
        $domainSessionId = new SessionId($session->getId());
        $researcher = new ResearcherId($session->getResearcher()->getId());
        $subjectOne = new SubjectId($session->getSubjectOne()->getId());
        $subjectTwo = new SubjectId($session->getSubjectTwo()->getId());
        $scenarioId = new ScenarioTemplateId($session->getScenario()->getId());


        if ($new) {
            $this->sessionService->createSession(
                $domainSessionId,
                $researcher,
                $subjectOne,
                $subjectTwo,
                $scenarioId
            );
        } else {
            $this->sessionService->updateSession(
                $domainSessionId,
                $researcher,
                $subjectOne,
                $subjectTwo,
                $scenarioId
            );
        }

        return new RedirectResponse($this->urlGenerator->generate('session_list'));
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function listAction(Request $request)
    {
        $paginator = new Paginator($request);
        $pagination = $paginator->getPaginatedArray(function ($sort, $page, $elementsPerPage) {
            try {
                return $this->sessionFinder->getPaginatedSessions($sort, $page, $elementsPerPage);
            } catch (\OutOfBoundsException $e) {
                throw new NotFoundHttpException();
            }
        });

        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'session/list.twig',
                $pagination->toArray()
            )
        );

        return $response;
    }
}
