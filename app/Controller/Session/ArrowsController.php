<?php

namespace BrasseursApplis\Arrows\App\Controller\Session;

use BrasseursApplis\Arrows\App\Finder\SessionFinder;
use Doctrine\ORM\ORMInvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class ArrowsController
{
    /** @var SessionFinder */
    private $sessionFinder;

    /** @var \Twig_Environment */
    private $twig;

    /**
     * IndexController constructor.
     *
     * @param SessionFinder     $sessionFinder
     * @param \Twig_Environment $twig
     */
    public function __construct(
        SessionFinder $sessionFinder,
        \Twig_Environment $twig
    ) {
        $this->sessionFinder = $sessionFinder;
        $this->twig = $twig;
    }

    /**
     * @param string $sessionId
     *
     * @return Response
     *
     * @throws ORMInvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function observerAction($sessionId)
    {
        $session = $this->sessionFinder->find($sessionId);

        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'arrows/observer.twig',
                [
                    'session' => $session,
                ]
            )
        );

        return $response;
    }

    /**
     * @param string $sessionId
     *
     * @return Response
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function positionOneAction($sessionId)
    {
        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'arrows/positionOne.twig',
                [
                    'sessionId' => $sessionId
                ]
            )
        );

        return $response;
    }

    /**
     * @param string $sessionId
     *
     * @return Response
     *
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function positionTwoAction($sessionId)
    {
        $response = new Response();
        $response->setContent(
            $this->twig->render(
                'arrows/positionTwo.twig',
                [
                    'sessionId' => $sessionId
                ]
            )
        );

        return $response;
    }
}
