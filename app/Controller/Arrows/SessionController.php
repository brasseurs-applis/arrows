<?php

namespace BrasseursApplis\Arrows\App\Controller\Arrows;

use Assert\AssertionFailedException;
use BrasseursApplis\Arrows\App\Controller\Util\Paginator;
use BrasseursApplis\Arrows\App\DTO\SessionDTO;
use BrasseursApplis\Arrows\App\Finder\SessionFinder;
use BrasseursApplis\Arrows\App\Form\SessionType;
use BrasseursApplis\Arrows\Id\SessionId;
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

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var \Twig_Environment */
    private $twig;

    /** @var UrlGenerator */
    private $urlGenerator;

    /**
     * SessionController constructor.
     *
     * @param SessionFinder       $sessionFinder
     * @param FormFactoryInterface $formFactory
     * @param \Twig_Environment    $twig
     * @param UrlGenerator         $urlGenerator
     */
    public function __construct(
        SessionFinder $sessionFinder,
        FormFactoryInterface $formFactory,
        \Twig_Environment $twig,
        UrlGenerator $urlGenerator
    ) {
        $this->sessionFinder = $sessionFinder;
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

        if ($new) {
            // Create session
        } else {
            // Update Session
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
