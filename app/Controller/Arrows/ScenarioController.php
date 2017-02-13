<?php

namespace BrasseursApplis\Arrows\App\Controller\Arrows;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\App\Controller\Util\Paginator;
use BrasseursApplis\Arrows\App\DTO\Helper\SequenceConverter;
use BrasseursApplis\Arrows\App\DTO\ScenarioDTO;
use BrasseursApplis\Arrows\App\Finder\ScenarioFinder;
use BrasseursApplis\Arrows\App\Form\ScenarioType;
use BrasseursApplis\Arrows\App\Security\AuthorizationUser;
use BrasseursApplis\Arrows\Id\ResearcherId;
use BrasseursApplis\Arrows\Id\ScenarioTemplateId;
use BrasseursApplis\Arrows\Service\ScenarioService;
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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ScenarioController
{
    /** @var ScenarioFinder */
    private $scenarioFinder;

    /** @var ScenarioService */
    private $scenarioService;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var \Twig_Environment */
    private $twig;

    /** @var UrlGenerator */
    private $urlGenerator;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * ScenarioController constructor.
     *
     * @param ScenarioFinder        $scenarioFinder
     * @param ScenarioService       $scenarioService
     * @param FormFactoryInterface  $formFactory
     * @param \Twig_Environment     $twig
     * @param UrlGenerator          $urlGenerator
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        ScenarioFinder $scenarioFinder,
        ScenarioService $scenarioService,
        FormFactoryInterface $formFactory,
        \Twig_Environment $twig,
        UrlGenerator $urlGenerator,
        TokenStorageInterface $tokenStorage
    ) {
        $this->scenarioFinder = $scenarioFinder;
        $this->scenarioService = $scenarioService;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return RedirectResponse
     *
     * @throws \InvalidArgumentException
     */
    public function createAction()
    {
        return new RedirectResponse($this->urlGenerator->generate('scenario_edit', [ 'scenarioId' => Uuid::uuid4() ]));
    }

    /**
     * @param Request $request
     * @param string  $scenarioId
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
    public function editAction(Request $request, $scenarioId)
    {
        /** @var AuthorizationUser $connectedUser */
        $connectedUser = $this->tokenStorage->getToken()->getUser();

        $scenario = $this->scenarioFinder->find($scenarioId);
        $new = false;

        if ($scenario === null) {
            $scenario = new ScenarioDTO($scenarioId);
            $new = true;
        }

        $form = $this->formFactory->create(ScenarioType::class, $scenario);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || ! $form->isValid()) {
            $response = new Response();
            $response->setContent(
                $this->twig->render(
                    'scenario/edit.twig',
                    [ 'form' => $form->createView(), 'scenarioId' => (string) $scenarioId ]
                )
            );

            return $response;
        }

        /** @var ScenarioDTO $scenario */
        $scenario = $form->getData();
        $domainScenarioTemplateId = new ScenarioTemplateId($scenario->getId());
        $sequences = SequenceConverter::toSequenceCollection($scenario->getSequences());

        if ($new) {
            $this->scenarioService->createScenario(
                $domainScenarioTemplateId,
                new ResearcherId($connectedUser->getId()),
                $scenario->getName(),
                $sequences
            );
        } else {
            $this->scenarioService->updateScenario(
                $domainScenarioTemplateId,
                new ResearcherId($connectedUser->getId()),
                $scenario->getName(),
                $sequences
            );
        }

        return new RedirectResponse($this->urlGenerator->generate('scenario_list'));
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
                return $this->scenarioFinder->getPaginatedScenarios($sort, $page, $elementsPerPage);
            } catch (\OutOfBoundsException $e) {
                throw new NotFoundHttpException();
            }
        });

        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'scenario/list.twig',
                $pagination->toArray()
            )
        );

        return $response;
    }
}
