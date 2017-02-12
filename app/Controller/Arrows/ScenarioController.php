<?php

namespace BrasseursApplis\Arrows\App\Controller\Arrows;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\App\Controller\Util\Paginator;
use BrasseursApplis\Arrows\App\DTO\ScenarioDTO;
use BrasseursApplis\Arrows\App\Finder\ScenarioFinder;
use BrasseursApplis\Arrows\App\Form\ScenarioType;
use BrasseursApplis\Arrows\Id\ScenarioTemplateId;
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

class ScenarioController
{
    /** @var ScenarioFinder */
    private $scenarioFinder;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var \Twig_Environment */
    private $twig;

    /** @var UrlGenerator */
    private $urlGenerator;

    /**
     * ScenarioController constructor.
     *
     * @param ScenarioFinder       $scenarioFinder
     * @param FormFactoryInterface $formFactory
     * @param \Twig_Environment    $twig
     * @param UrlGenerator         $urlGenerator
     */
    public function __construct(
        ScenarioFinder $scenarioFinder,
        FormFactoryInterface $formFactory,
        \Twig_Environment $twig,
        UrlGenerator $urlGenerator
    ) {
        $this->scenarioFinder = $scenarioFinder;
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
        $scenarioTemplateId = new ScenarioTemplateId($scenario->getId());

        if ($new) {
            // Create scenario
        } else {
            // Update Scenario
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
